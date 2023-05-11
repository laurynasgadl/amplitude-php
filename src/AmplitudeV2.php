<?php

namespace Luur\Amplitude;

use GuzzleHttp\Psr7\Request;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

class AmplitudeV2 extends AbstractAmplitude
{
    public const API_URI = 'https://api2.amplitude.com/2/httpapi';

    public function send(JsonSerializable $message): ResponseInterface
    {
        $params = [
            'api_key' => $this->apiKey,
            'events' => $message->toArray(),
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $request = new Request('POST', '', $headers, json_encode($params));

        return $this->client->sendRequest($request);
    }

    public function getBaseURI(): string
    {
        return self::API_URI;
    }
}
