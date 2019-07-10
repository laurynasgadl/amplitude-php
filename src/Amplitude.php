<?php

namespace Luur\Amplitude;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use JsonSerializable;

class Amplitude
{
    use LoggerAwareTrait;

    /**
     * Amplitude service URL
     *
     * @var string
     */
    const API_URL = 'https://api.amplitude.com/httpapi';

    /**
     * Possible HTTP Status Codes
     *
     * https://help.amplitude.com/hc/en-us/articles/204771828-HTTP-API#http-status-codes-retrying-failed-requests
     *
     * @var array
     */
    const HTTP_CODES = [
        400 => 'Request malformed',
        413 => 'Too many events in request',
        429 => 'Too many requests for the device',
        500 => 'Error while handling the request',
        502 => 'Error while handling the request',
        504 => 'Error while handling the request',
        503 => 'Server error',
    ];

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var Message
     */
    protected $message;

    /**
     * Amplitude constructor.
     * @param string $apiKey
     * @param LoggerInterface|null $logger
     */
    public function __construct($apiKey, LoggerInterface $logger = null)
    {
        $this->apiKey = $apiKey;

        if (!$logger) {
            $logger = new NullLogger();
        }

        $this->setLogger($logger);
    }

    /**
     * @param JsonSerializable $message
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function send(JsonSerializable $message)
    {
        $http = $this->getHttpClient();

        try {
            return $http->request('POST', '', [
                'form_params' => [
                    'api_key' => $this->apiKey,
                    'event'   => json_encode($message, JSON_NUMERIC_CHECK),
                ]
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

    /**
     * @return Client
     */
    protected function getHttpClient()
    {
        return new Client([
            'base_uri' => self::API_URL,
            'timeout'  => 60.0,
        ]);
    }
}