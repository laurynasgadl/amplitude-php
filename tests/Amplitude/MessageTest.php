<?php

namespace Luur\Amplitude\Tests;

use Luur\Amplitude\Event;
use Luur\Amplitude\Message;
use Luur\Amplitude\Exceptions\InvalidDataException;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testThrowsInvalidDataException()
    {
        $this->expectException(InvalidDataException::class);
        new Message(['1', '2', '3']);
    }

    public function testCreatesMessage()
    {
        $mess = new Message();
        $this->assertTrue($mess instanceof Message);
        return $mess;
    }

    /**
     * @depends testCreatesMessage
     * @param Message $message
     * @return Message
     */
    public function testCreatesEmptyBuffer(Message $message)
    {
        $this->assertEmpty($message->getBuffer());
        return $message;
    }

    /**
     * @return Message
     * @throws InvalidDataException
     */
    public function testCreatesNonEmptyBuffer()
    {
        $buffer = [
            $this->buildEvent(),
            $this->buildEvent(),
            $this->buildEvent(),
        ];
        $mess   = new Message($buffer);
        $this->assertSameSize($buffer, $mess->getBuffer());
        return $mess;
    }

    /**
     * @return Event
     */
    public function buildEvent()
    {
        return new Event();
    }

    /**
     * @depends testCreatesNonEmptyBuffer
     * @param Message $message
     */
    public function testClearsBuffer(Message $message)
    {
        $result = $message->clear();
        $this->assertNotEmpty($result);
        $this->assertEmpty($message->getBuffer());
    }

    /**
     * @depends testCreatesNonEmptyBuffer
     * @param Message $message
     */
    public function testConvertsMultipleEventsToString(Message $message)
    {
        $result = json_encode($message, JSON_NUMERIC_CHECK);
        $this->assertEquals(json_encode($message->getBuffer(), JSON_NUMERIC_CHECK), $result);
    }

    public function testConvertsSingleEventToString()
    {
        $message = new Message([
            $this->buildEvent()
        ]);
        $result  = json_encode($message, JSON_NUMERIC_CHECK);
        $this->assertEquals(json_encode($message->getBuffer()[0], JSON_NUMERIC_CHECK), $result);
    }

    /**
     * @depends testCreatesEmptyBuffer
     * @param Message $message
     */
    public function testConvertsEmptyBufferToString(Message $message)
    {
        $result = json_encode($message, JSON_NUMERIC_CHECK);
        $this->assertEquals(json_encode($message->getBuffer(), JSON_NUMERIC_CHECK), $result);
    }

    /**
     * @depends testCreatesNonEmptyBuffer
     * @param Message $message
     */
    public function testAddsEvent(Message $message)
    {
        $count = count($message->getBuffer());
        $message->addEvent($this->buildEvent());
        $this->assertCount($count + 1, $message->getBuffer());
    }

    /**
     * @depends testCreatesNonEmptyBuffer
     * @param Message $message
     * @throws InvalidDataException
     */
    public function testAddsEvents(Message $message)
    {
        $count = count($message->getBuffer());
        $message->addEvents([
            $this->buildEvent(),
            $this->buildEvent(),
            $this->buildEvent(),
        ]);
        $this->assertCount($count + 3, $message->getBuffer());
    }

    /**
     * @depends testCreatesEmptyBuffer
     * @param Message $message
     */
    public function testGetsEmptyBuffer(Message $message)
    {
        $this->assertCount(0, $message->getBuffer());
    }
}
