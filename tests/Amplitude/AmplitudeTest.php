<?php

namespace Luur\Amplitude\Tests;

use GuzzleHttp\Psr7\Response;
use Luur\Amplitude\Event;
use Luur\Amplitude\Message;
use Luur\Amplitude\Amplitude;
use Luur\Amplitude\Exceptions\InvalidDataException;
use GuzzleHttp\Client;
use Psr\Log\NullLogger;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\GuzzleException;

class AmplitudeTest extends TestCase
{
    public function testCanConstructAmplitude()
    {
        $amp = new Amplitude('test-test');
        $this->assertTrue($amp instanceof Amplitude);
    }

    public function testCanConstructAmplitudeWithLogger()
    {
        $amp = new Amplitude('test-test', new NullLogger());
        $this->assertTrue($amp instanceof Amplitude);
    }

    /**
     * @throws GuzzleException
     */
    public function testHandlesEventSending()
    {
        $this->expectException(GuzzleException::class);
        $amp = new Amplitude('test-test');
        $amp->send($this->buildEvent());
    }

    /**
     * @return Event
     */
    public function buildEvent()
    {
        return new Event();
    }

    public function testReturnsResponse()
    {
        $clientMock = $this->getMockBuilder(Client::class)
            ->onlyMethods(['request'])
            ->getMock();

        $response = new Response(200, [], 'test');
        $clientMock->method('request')->willReturn($response);

        $ampMock = $this->getMockBuilder(Amplitude::class)
            ->setConstructorArgs(['test-test'])
            ->onlyMethods(['getHttpClient'])
            ->getMock();

        $ampMock->method('getHttpClient')->willReturn($clientMock);

        $this->assertEquals($response, $ampMock->send($this->buildEvent()));
    }

    /**
     * @throws GuzzleException
     * @throws InvalidDataException
     */
    public function testThrowsGuzzleException()
    {
        $this->expectException(GuzzleException::class);
        $amp = new Amplitude('test-test');
        $amp->send($this->buildMessage());
    }

    /**
     * @return Message
     * @throws InvalidDataException
     */
    public function buildMessage()
    {
        return new Message([
            $this->buildEvent(),
            $this->buildEvent(),
            $this->buildEvent(),
        ]);
    }
}
