<?php

namespace Luur\Amplitude\Tests;

use Exception;
use GuzzleHttp\Psr7\Response;
use Luur\Amplitude\Event;
use Luur\Amplitude\Amplitude;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AmplitudeTest extends TestCase
{
    public function testConstructsAmplitude(): void
    {
        $amp = new Amplitude('test');
        $this->assertTrue($amp instanceof Amplitude);
    }

    public function testConstructsAmplitudeWithClient(): void
    {
        $amp = new Amplitude('test', new Client());
        $this->assertTrue($amp instanceof Amplitude);
    }

    public function testHandlesEventSending(): void
    {
        $exception = new Exception('test exception');
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())->method('sendRequest')->willThrowException($exception);

        $this->expectException(get_class($exception));

        $amp = new Amplitude('test', $mockClient);
        $amp->send(new Event());
    }

    public function testReturnsResponse(): void
    {
        $response = new Response(200, [], 'test');

        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())->method('sendRequest')->willReturn($response);

        $amp = new Amplitude('test', $mockClient);

        $this->assertEquals($response, $amp->send(new Event()));
    }

    public function testSetsClient(): void
    {
        $amp = new Amplitude('test');

        $exception = new Exception('test exception');
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())->method('sendRequest')->willThrowException($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $amp->setClient($mockClient);
        $amp->send(new Event());
    }
}
