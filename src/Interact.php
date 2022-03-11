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
        return Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->post($this->getUrl($uri), $postData);
    }

    public function get(string $uri, ?array $data = [], ?array $headers = [])
    {
        return Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->get($this->getUrl($uri), array_merge($data, ['paginationUrl' => request()->url()]));
    }

    public function patch(string $uri, array $data, ?array $headers = [])
    {
        return Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->patch($this->getUrl($uri), $data);
    }

    public function delete(string $uri)
    {
        return Http::withHeaders($this->defaultHeaders())->delete($this->getUrl($uri));
    }

    private function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
            'x-api-key' => $this->clientCredentials['clientId'] ?? '',
            'x-api-user-ip' => request()->ip() ?: ''
        ];
    }

    private function getUrl(string $uri): string
    {
        return str_replace('//', '/', $this->apiUrl . '/' . $uri);
    }
}