<?php

namespace Luur\Amplitude\Tests;

use ErrorException;
use Luur\Amplitude\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testCreatesEvent(): Event
    {
        $event = new Event();
        $this->assertTrue($event instanceof Event);

        return $event;
    }

    /**
     * @depends testCreatesEvent
     * @param Event $event
     */
    public function testSetsFromArray(Event $event): void
    {
        $event->set([
            'user_id' => 123456,
            'time' => '123456789',
            'event_properties' => [],
            'price' => '123456789.00',
        ]);

        $this->assertIsInt($event->time);
        $this->assertIsFloat($event->price);
        $this->assertIsString($event->user_id);
        $this->assertIsArray($event->event_properties);
    }

    /**
     * @depends testCreatesEvent
     * @param Event $event
     */
    public function testEventSetsCorrectType(Event $event): void
    {
        $event->user_id = 123456;
        $this->assertIsString($event->user_id);

        $event->time = '123456789';
        $this->assertIsInt($event->time);

        $event->event_properties = 'test';
        $this->assertIsArray($event->event_properties);

        $event->price = '123456789.00';
        $this->assertIsFloat($event->price);
    }

    /**
     * @depends testCreatesEvent
     * @param Event $event
     */
    public function testThrowsExceptionOnInvalidProp(Event $event): void
    {
        $this->expectException(ErrorException::class);
        $this->assertNull($event->test);
    }
}
