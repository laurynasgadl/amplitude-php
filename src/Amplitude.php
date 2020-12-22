<?php

namespace Luur\Amplitude;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Amplitude
{
    use LoggerAwareTrait;

    public const API_URL = 'https://api.amplitude.com/httpapi';

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

    protected string $apiKey;

    protected Message $message;

    public function __construct(string $apiKey, LoggerInterface $logger = null)
    {
        $this->apiKey = $apiKey;

        if (!$logger) {
            $logger = new NullLogger();
        }

        $this->setLogger($logger);
    }

    /**
     * @param JsonSerializable $message
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function send(JsonSerializable $message): ResponseInterface
    {
        $http = $this->getHttpClient();

        try {
            return $http->request('POST', '', [
                'form_params' => [
                    'api_key' => $this->apiKey,
                    'event'   => json_encode($message, JSON_NUMERIC_CHECK),
                ],
            ]);
        } catch (GuzzleException $exception) {
            $this->logger->error('Amplitude POST request failed', [
                'code'    => in_array($exception->getCode(), self::HTTP_CODES) ?
                    self::HTTP_CODES[$exception->getCode()] : $exception->getCode(),
                'message' => $exception->getMessage(),
                'trace'   => $exception->getTrace(),
            ]);

            throw $exception;
        }
    }

    protected function getHttpClient(): Client
    {
        return new Client([
            'base_uri' => self::API_URL,
            'timeout'  => 60.0,
        ]);
    }
}
