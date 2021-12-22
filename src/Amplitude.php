<?php

namespace Luur\Amplitude;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use JsonSerializable;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Amplitude
{
    public const API_URL = 'https://api.amplitude.com/httpapi';

    protected string $apiKey;

    protected ClientInterface $client;

    public function __construct(string $apiKey, ClientInterface $client = null)
    {
        $this->apiKey = $apiKey;
        $this->client = $client ?? $this->getDefaultClient();
    }

    /**
     * @param JsonSerializable $message
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function send(JsonSerializable $message): ResponseInterface
    {
        $params = [
            'api_key' => $this->apiKey,
            'event' => json_encode($message, JSON_NUMERIC_CHECK),
        ];

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $request = new Request('POST', '', $headers, http_build_query($params));

        return $this->client->sendRequest($request);
    }

    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    protected function getDefaultClient(): ClientInterface
    {
        return new Client([
            'base_uri' => self::API_URL,
            'timeout' => 60.0,
        ]);
    }
}
