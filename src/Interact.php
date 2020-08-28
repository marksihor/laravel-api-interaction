<?php

namespace MarksIhor\ApiInteraction;

use Illuminate\Support\Facades\Http;

class Interact
{
    protected $accessToken;
    protected $apiUrl;
    protected $clientCredentials;

    public function __construct(string $apiUrl, array $clientCredentials)
    {
        $this->apiUrl = $apiUrl;
        $this->clientCredentials = $clientCredentials;
        $this->accessToken = (new Auth)->getAccessToken($apiUrl, $clientCredentials);
    }

    public function post(string $uri, ?array $postData = [], ?array $headers = [])
    {
        $response = Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->post($this->getUrl($uri), $postData);

        return $response->json();
    }

    public function get(string $uri, ?array $data = [], ?array $headers = [])
    {
        $response = Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->get($this->getUrl($uri), array_merge($data, ['paginationUrl' => request()->url()]));

        return $response->json();
    }

    public function patch(string $uri, array $data, ?array $headers = [])
    {
        $response = Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->patch($this->getUrl($uri), $data);

        return $response->json();
    }

    public function delete(string $uri)
    {
        $response = Http::withHeaders($this->defaultHeaders())->delete($this->getUrl($uri));

        return $response->json();
    }

    private function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
            'x-api-key' => $this->clientCredentials['clientId'] ?? ''
        ];
    }

    private function getUrl(string $uri): string
    {
        return str_replace('//', '/', $this->apiUrl . '/' . $uri);
    }
}