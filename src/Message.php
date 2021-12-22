<?php

namespace Luur\Amplitude;

use Luur\Amplitude\Exceptions\InvalidDataException;
use JsonSerializable;

class Message implements JsonSerializable
{
    protected array $buffer;

    /**
     * Message constructor.
     * @param array $events
     * @throws InvalidDataException
     */
    public function __construct(array $events = [])
    {
        $this->buffer = $this->validatedEvents($events);
    }

    /**
     * @param array $events
     * @return array
     * @throws InvalidDataException
     */
    private function validatedEvents(array $events): array
    {
        foreach ($events as $event) {
            if (!$event instanceof Event) {
                throw new InvalidDataException('All array objects need to be an instance of `Event`');
            }
        }

        return $events;
    }

    public function addEvent(Event $event): void
    {
        $this->buffer[] = $event;
    }

    /**
     * @param Event[] $events
     * @throws InvalidDataException
     */
    public function addEvents(array $events): void
    {
        $this->buffer = array_merge($this->buffer, $this->validatedEvents($events));
    }

    public function clear(): array
    {
        $copy = $this->buffer;
        $this->buffer = [];

        return $copy;
    }

    public function getBuffer(): array
    {
        return $this->buffer;
    }

    protected function toArray(): array
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
