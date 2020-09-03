<?php

namespace MarksIhor\ApiInteraction;

use Illuminate\Support\Facades\Http;

class Auth
{
    public function getAccessToken(string $apiUrl, array $apiCredentials, ?bool $cache = true)
    {
        $clientId = $apiCredentials['clientId'] ?? null;
        $clientSecret = $apiCredentials['clientSecret'] ?? null;

        if ($apiUrl && $clientId && $clientSecret) {
            if ($cache) {
                $cacheKey = md5($apiUrl . $clientId . $clientSecret);
                $token = cache($cacheKey);
            }

            if ($cache && isset($token)) {
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
                    if ($cache) {
                        cache([$cacheKey => $response->json()['access_token']], $response->json()['expires_in']);
                    }

                    return $response->json()['access_token'];
                }
            }
        }

        return null;
    }
}