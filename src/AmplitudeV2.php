<?php

namespace Luur\Amplitude;

use JsonSerializable;

class AmplitudeV2 extends AbstractAmplitude
{
    protected function getApiUrl(): string
    {
        return 'https://api2.amplitude.com/2/httpapi';
    }

    protected function formatParams(JsonSerializable $message): string
    {
        return json_encode([
            'api_key' => $this->apiKey,
            'events' => [$message->toArray()],
        ]);
    }
}
