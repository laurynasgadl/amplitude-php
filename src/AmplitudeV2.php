<?php

namespace Luur\Amplitude;

use GuzzleHttp\Psr7\Request;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

class AmplitudeV2 extends AbstractAmplitude
{
    public const API_URL = 'https://api2.amplitude.com/2/httpapi';

    public function send(JsonSerializable $message): ResponseInterface
    {
        $params = [
            'api_key' => $this->apiKey,
            'events' => [$message->toArray()],
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $request = new Request('POST', self::API_URL, $headers, json_encode($params));

        return $this->client->sendRequest($request);
    }
}
