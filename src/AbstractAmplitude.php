<?php

namespace Luur\Amplitude;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use JsonSerializable;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractAmplitude
{
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
        $params = $this->formatParams($message);

        $headers = [
            'Content-Type' => $this->getContentType(),
        ];

        $request = new Request('POST', $this->getApiUrl(), $headers, $params);

        return $this->client->sendRequest($request);
    }

    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    protected function getDefaultClient(): ClientInterface
    {
        return new Client([
            'timeout' => 60.0,
        ]);
    }

    protected function getContentType(): string
    {
        return 'application/x-www-form-urlencoded';
    }

    abstract protected function getApiUrl(): string;

    abstract protected function formatParams(JsonSerializable $message): string;
}
