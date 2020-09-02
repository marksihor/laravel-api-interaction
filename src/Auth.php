<?php

namespace MarksIhor\ApiInteraction;

use Illuminate\Support\Facades\Http;

class Auth
{
    public function getAccessToken(string $apiUrl, array $apiCredentials)
    {
        $clientId = $apiCredentials['clientId'] ?? null;
        $clientSecret = $apiCredentials['clientSecret'] ?? null;

        if ($apiUrl && $clientId && $clientSecret) {
            $cacheKey = md5($apiUrl . $clientId . $clientSecret);

            $token = cache($cacheKey);

            if ($token) {
                return $token;
            } else {
                $response = Http::withHeaders([
                    'x-api-key' => $clientId
                ])->post($apiUrl . '/oauth/token', [
                    'grant_type' => "client_credentials",
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'scope' => '*',
                ]);

                if (isset($response->json()['access_token'])) {
                    cache([$cacheKey => $response->json()['access_token']], $response->json()['expires_in']);

                    return cache($cacheKey);
                }
            }
        }

        return null;
    }
}