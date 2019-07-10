<?php

namespace Luur\Amplitude;

use Luur\Amplitude\Exceptions\InvalidDataException;
use JsonSerializable;

class Message implements JsonSerializable
{
    /**
     * @var array
     */
    protected $buffer;

    /**
     * Message constructor.
     *
     * @param array $events
     * @throws InvalidDataException
     */
    public function __construct(array $events = [])
    {
        $this->buffer = $this->validateEvents($events);
    }

    /**
     * @param array $events
     * @return array
     * @throws InvalidDataException
     */
    private function validateEvents(array $events)
    {
        foreach ($events as $event) {
            if (!$event instanceof Event) {
                throw new InvalidDataException('All array objects need to be an instance of `Event`');
            }
        }
        return $events;
    }

    /**
     * @param Event $event
     */
    public function addEvent(Event $event)
    {
        $this->buffer[] = $event;
    }

    /**
     * @param array $events
     * @throws InvalidDataException
     */
    public function addEvents(array $events)
    {
        $this->buffer = array_merge($this->buffer, $this->validateEvents($events));
    }

    /**
     * @return array
     */
    public function clear()
    {
        $copy         = $this->buffer;
        $this->buffer = [];
        return $copy;
    }

    /**
     * @return array
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    protected function toArray()
    {
        $array = [];

        foreach ($this->buffer as $event) {
            $array[] = $event->toArray();
        }

        if (count($array) === 1) {
            return $array[0];
        }

        return $array;
    }
}