<?php

namespace App\Website\Services;

class PushNotificationService
{
    private $projectId;

    private $clientEmail;

    private $privateKey;

    public function __construct()
    {
        $this->projectId = config('website.runtime.firebase_project_id');
        $this->clientEmail = config('website.runtime.firebase_client_email');
        $this->privateKey = str_replace('\\n', "\n", config('website.runtime.firebase_private_key'));
    }

    private function getAccessToken()
    {
        $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
        $jwtClaim = [
            'iss' => $this->clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        // Base64 encode
        $segments = [];
        $segments[] = rtrim(strtr(base64_encode(json_encode($jwtHeader)), '+/', '-_'), '=');
        $segments[] = rtrim(strtr(base64_encode(json_encode($jwtClaim)), '+/', '-_'), '=');

        // Sign JWT
        openssl_sign(
            $segments[0].'.'.$segments[1],
            $signature,
            $this->privateKey,
            'SHA256'
        );

        $segments[] = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
        $jwt = implode('.', $segments);

        // Request access token
        $response = file_get_contents('https://oauth2.googleapis.com/token', false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]),
            ],
        ]));
        $result = json_decode($response, true);

        return $result['access_token'] ?? null;
    }

    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();

        if (! $accessToken) {
            return 'Failed to generate access token';
        }

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
            ],
        ];

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $accessToken",
                'Content-Type: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        echo $response;

        return $error ? $error : $response;

    }
}
