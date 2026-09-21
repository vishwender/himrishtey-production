<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\Push;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PushSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);
        $id = Auth::guard('member')->user()->id;
        DB::connection('site')->table('push_subscriptions')->updateOrInsert(
            ['user_id' => $id],
            [
                'endpoint' => $request->endpoint,
                'p256dh' => $request->keys['p256dh'],
                'auth' => $request->keys['auth'],
                'user_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }

    public function sendBrowserNotification()
    {
        $id = Auth::guard('member')->user()->id;
        $user = Push::where('user_id', $id)->first();
        $endpoint = $user->endpoint;
        $p256dh = $user->p256dh;
        $auth = $user->auth;

        $payload = json_encode([
            'title' => 'Hello from Laravel!',
            'body' => 'This is a web push notification without any package',
        ]);

        // Generate VAPID token
        $vapidHeader = $this->generateVapidHeader($endpoint);

        // Encrypt payload
        $encrypted = $this->encryptPayload($payload, $p256dh, $auth);

        $headers = [
            'TTL: 60',
            'Authorization: '.$vapidHeader,
            'Content-Encoding: aes128gcm',
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encrypted);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        return response()->json([
            'response' => $response,
            'error' => $error,
        ]);
    }

    /**
     * Generate VAPID header
     */
    private function generateVapidHeader($endpoint)
    {
        $aud = parse_url($endpoint, PHP_URL_SCHEME).'://'.parse_url($endpoint, PHP_URL_HOST);

        $jwtHeader = ['alg' => 'ES256', 'typ' => 'JWT'];
        $jwtClaims = [
            'aud' => $aud,
            'exp' => time() + 43200, // 12 hours
            'sub' => 'mailto:admin@yourdomain.com',
        ];

        $header = rtrim(strtr(base64_encode(json_encode($jwtHeader)), '+/', '-_'), '=');
        $claims = rtrim(strtr(base64_encode(json_encode($jwtClaims)), '+/', '-_'), '=');
        $jwtUnsigned = $header.'.'.$claims;

        $privateKeyPem = $this->convertVapidPrivateKeyToPem(config('website.runtime.vapid_private_key'));

        openssl_sign($jwtUnsigned, $signature, $privateKeyPem, OPENSSL_ALGO_SHA256);
        $jwtSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $jwt = $jwtUnsigned.'.'.$jwtSignature;

        return 'WebPush '.$jwt.',k='.config('website.runtime.vapid_public_key').',alg=ES256';
    }

    /**
     * Convert VAPID private key to PEM format
     */
    private function convertVapidPrivateKeyToPem($privateKey)
    {
        $decoded = base64_decode(strtr($privateKey, '-_', '+/'));
        $pem = "-----BEGIN EC PRIVATE KEY-----\n".
               chunk_split(base64_encode($decoded), 64, "\n").
               "-----END EC PRIVATE KEY-----\n";

        return $pem;
    }

    /**
     * Simple placeholder encryption (for test purpose)
     * You must replace this with full AES128GCM payload encryption
     */
    private function encryptPayload($payload, $p256dh, $auth)
    {
        // In production, perform real WebPush AES-GCM encryption here
        // Returning plaintext only for testing endpoint reach
        return $payload;
    }
}
