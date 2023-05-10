<?php

namespace Luur\Amplitude;

use JsonSerializable;

class Amplitude extends AbstractAmplitude
{
    protected function getApiUrl(): string
    {
        return 'https://api.amplitude.com/httpapi';
    }

    protected function formatParams(JsonSerializable $message): string
    {
        return http_build_query([
            'api_key' => $this->apiKey,
            'event' => json_encode($message, JSON_NUMERIC_CHECK),
        ]);
    }
}
