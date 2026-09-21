<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Firebase_notification
{
    private $serviceAccount;
    private $projectId;

    public function __construct()
    {
        $json = file_get_contents(APPPATH . 'config/firebase-service-account.json');

        $this->serviceAccount = json_decode($json, true);
        $this->projectId = $this->serviceAccount['project_id'];
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function getAccessToken()
    {
        $now = time();

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $payload = [
            'iss'   => $this->serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => $this->serviceAccount['token_uri'],
            'iat'   => $now,
            'exp'   => $now + 3600
        ];

        $jwtHeader  = $this->base64UrlEncode(json_encode($header));
        $jwtPayload = $this->base64UrlEncode(json_encode($payload));

        $signatureInput = $jwtHeader . "." . $jwtPayload;

        openssl_sign(
            $signatureInput,
            $signature,
            $this->serviceAccount['private_key'],
            'SHA256'
        );

        $jwt = $signatureInput . '.' . $this->base64UrlEncode($signature);

        $postFields = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt
        ]);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->serviceAccount['token_uri'],
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postFields
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['access_token'] ?? false;
    }

    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'status' => false,
                'message' => 'Unable to generate access token'
            ];
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $data
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return [
                'status' => false,
                'message' => curl_error($ch)
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}