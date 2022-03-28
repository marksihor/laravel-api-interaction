<?php

namespace MarksIhor\ApiInteraction;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use MarksIhor\ApiInteraction\Models\RequestLog;

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

        $this->logRequest($this->getUrl($uri), 'POST', array_merge($this->defaultHeaders(), $headers), $postData, $response);

        return $response;
    }

    public function get(string $uri, ?array $data = [], ?array $headers = [])
    {
        $response = Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->get($this->getUrl($uri), array_merge($data, ['paginationUrl' => request()->url()]));

        $this->logRequest($this->getUrl($uri), 'GET', array_merge($this->defaultHeaders(), $headers), array_merge($data, ['paginationUrl' => request()->url()]), $response);

        return $response;
    }

    public function patch(string $uri, array $data, ?array $headers = [])
    {
        $response = Http::withHeaders(array_merge($this->defaultHeaders(), $headers))
            ->patch($this->getUrl($uri), $data);

        $this->logRequest($this->getUrl($uri), 'PATCH', array_merge($this->defaultHeaders(), $headers), $data, $response);

        return $response;
    }

    public function delete(string $uri)
    {
        $response = Http::withHeaders($this->defaultHeaders())->delete($this->getUrl($uri));

        $this->logRequest($this->getUrl($uri), 'DELETE', array_merge($this->defaultHeaders(), $this->defaultHeaders()), null, $response);

        return $response;
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
        return trim($this->apiUrl, '/') . str_replace('//', '/', '/' . $uri);
    }

    private function logRequest(string $endpoint, string $method, ?array $headers, ?array $requestData, ?Response $response): void
    {
        if (config('laravel_api_interaction.log_requests')) {
            RequestLog::create([
                'endpoint' => $endpoint,
                'method' => $method,
                'request_headers' => $headers,
                'request_data' => $requestData,
                'code' => $response->status(),
                'response_data' => $response->json(),
                'from_endpoint' => request()->url()
            ]);
        }
    }
}
