<?php

namespace Luur\Amplitude\Tests;

use Exception;
use GuzzleHttp\Psr7\Response;
use Luur\Amplitude\Event;
use Luur\Amplitude\AmplitudeV2;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AmplitudeV2Test extends TestCase
{
    public function testConstructsAmplitude(): void
    {
        $amp = new AmplitudeV2('test');
        $this->assertTrue($amp instanceof AmplitudeV2);
    }

    public function testConstructsAmplitudeWithClient(): void
    {
        $amp = new AmplitudeV2('test', new Client());
        $this->assertTrue($amp instanceof AmplitudeV2);
    }

    public function testHandlesEventSending(): void
    {
        $exception = new Exception('test exception');
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())->method('sendRequest')->willThrowException($exception);

        $this->expectException(get_class($exception));

        $amp = new AmplitudeV2('test', $mockClient);
        $amp->send(new Event());
    }

    public function testReturnsResponse(): void
    {
        $response = new Response(200, [], 'test');

        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())->method('sendRequest')->willReturn($response);

        $amp = new AmplitudeV2('test', $mockClient);

        $this->assertEquals($response, $amp->send(new Event()));
    }

    public function testSetsClient(): void
    {
        $amp = new AmplitudeV2('test');

        $exception = new Exception('test exception');
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())->method('sendRequest')->willThrowException($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $amp->setClient($mockClient);
        $amp->send(new Event());
    }
}
