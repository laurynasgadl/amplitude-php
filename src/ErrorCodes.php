<?php

namespace Luur\Amplitude;

/**
 * Helper class for Amplitude API error handling
 */
class ErrorCodes
{
    /**
     * Possible HTTP Status Codes
     *
     * https://help.amplitude.com/hc/en-us/articles/204771828-HTTP-API#http-status-codes-retrying-failed-requests
     *
     * @var array
     */
    public const HTTP_CODES = [
        400 => 'Request malformed',
        413 => 'Too many events in request',
        429 => 'Too many requests for the device',
        500 => 'Error while handling the request',
        502 => 'Error while handling the request',
        504 => 'Error while handling the request',
        503 => 'Server error',
    ];

    public static function getText(int $code): ?string
    {
        return self::HTTP_CODES[$code] ?? null;
    }
}
