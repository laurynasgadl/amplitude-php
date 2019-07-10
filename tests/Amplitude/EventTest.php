<?php

use Luur\Amplitude\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testCreatesEvent()
    {
        $event = $this->buildEvent();
        $this->assertTrue($event instanceof Event);
        return $event;
    }

    /**
     * @return Event
     */
    public function buildEvent()
    {
        return new Event();
    }

    /**
     * @depends testCreatesEvent
     * @param Event $event
     */
    public function testSetsFromArray(Event $event)
    {
        $event->set([
            'user_id'          => 123456,
            'time'             => '123456789',
            'event_properties' => [],
            'price'            => '123456789.00',
        ]);

        $this->assertTrue(is_int($event->time));
        $this->assertTrue(is_float($event->price));
        $this->assertTrue(is_string($event->user_id));
        $this->assertTrue(is_array($event->event_properties));
    }

    /**
     * @depends testCreatesEvent
     * @param Event $event
     */
    public function testEventSetsCorrectType(Event $event)
    {
        $event->user_id = 123456;
        $this->assertTrue(is_string($event->user_id));

        $event->time = '123456789';
        $this->assertTrue(is_int($event->time));

        $event->event_properties = 'test';
        $this->assertTrue(is_array($event->event_properties));

        $event->price = '123456789.00';
        $this->assertTrue(is_float($event->price));
    }

    /**
     * @depends testCreatesEvent
     * @param Event $event
     */
    public function testThrowsExceptionOnInvalidProp(Event $event)
    {
        $this->expectException(ErrorException::class);
        $event->test;
    }
}