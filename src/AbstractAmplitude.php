<?php

namespace Luur\Amplitude;

use GuzzleHttp\Client;
use JsonSerializable;
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

    abstract public function send(JsonSerializable $message): ResponseInterface;

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
}
