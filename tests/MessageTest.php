<?php

namespace Luur\Amplitude\Tests;

use Luur\Amplitude\Event;
use Luur\Amplitude\Message;
use Luur\Amplitude\Exceptions\InvalidDataException;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testThrowsInvalidDataException(): void
    {
        $this->expectException(InvalidDataException::class);
        new Message(['1', '2', '3']);
    }

    public function testCreatesMessage(): Message
    {
        $message = new Message();
        $this->assertTrue($message instanceof Message);

        return $message;
    }

    /**
     * @depends testCreatesMessage
     * @param Message $message
     * @return Message
     */
    public function testCreatesEmptyBuffer(Message $message): Message
    {
        $this->assertEmpty($message->getBuffer());

        return $message;
    }

    /**
     * @throws InvalidDataException
     * @return Message
     */
    public function testCreatesNonEmptyBuffer(): Message
    {
        $buffer = [
            new Event(),
            new Event(),
            new Event(),
        ];
        $message = new Message($buffer);
        $this->assertSameSize($buffer, $message->getBuffer());

        return $message;
    }

    /**
     * @depends testCreatesNonEmptyBuffer
     * @param Message $message
     */
    public function testClearsBuffer(Message $message): void
    {
        $result = $message->clear();
        $this->assertNotEmpty($result);
        $this->assertEmpty($message->getBuffer());
    }

    /**
     * @depends testCreatesNonEmptyBuffer
     * @param Message $message
     */
    public function testConvertsMultipleEventsToString(Message $message): void
    {
        $result = json_encode($message, JSON_NUMERIC_CHECK);
        $this->assertEquals(json_encode($message->getBuffer(), JSON_NUMERIC_CHECK), $result);
    }

    public function testConvertsSingleEventToString(): void
    {
        $message = new Message([
            new Event(),
        ]);
        $result = json_encode($message, JSON_NUMERIC_CHECK);
        $this->assertEquals(json_encode($message->getBuffer()[0], JSON_NUMERIC_CHECK), $result);
    }

    /**
     * @depends testCreatesEmptyBuffer
     * @param Message $message
     */
    public function testConvertsEmptyBufferToString(Message $message): void
    {
        $result = json_encode($message, JSON_NUMERIC_CHECK);
        $this->assertEquals(json_encode($message->getBuffer(), JSON_NUMERIC_CHECK), $result);
    }

    /**
     * @depends testCreatesNonEmptyBuffer
     * @param Message $message
     */
    public function testAddsEvent(Message $message): void
    {
        $count = count($message->getBuffer());
        $message->addEvent(new Event());
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
            new Event(),
            new Event(),
            new Event(),
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
