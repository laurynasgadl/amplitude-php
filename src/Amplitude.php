<?php

namespace Luur\Amplitude;

use GuzzleHttp\Psr7\Request;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

class Amplitude extends AbstractAmplitude
{
    public const API_URI = 'https://api.amplitude.com/httpapi';

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

    public function getBaseURI(): string
    {
        return self::API_URI;
    }
}
