# Amplitude PHP client
Straightforward Amplitude client package.

## Installation
`composer require laurynasgadl/amplitude-php`

## Example

### Sending a single event
```php
use Luur\Amplitude\Amplitude;
use Luur\Amplitude\Event;

$amplitude = new Amplitude('api-key');

$event = new Event();
$event->user_id = '123456';
$event->event_type = 'test-event';

$result = $amplitude->send($event);
```

### Sending multiple events
```php
use Luur\Amplitude\Amplitude;
use Luur\Amplitude\Message;
use Luur\Amplitude\Event;

$amplitude = new Amplitude('api-key');

$event_1 = new Event([
    'user_id' => '123456',
    'event_type' => 'test-event',
]);

$event_2 = new Event([
    'user_id' => '987654',
    'event_type' => 'test-event',
]);

$message = new Message([
    $event_1,
    $event_2,
]);

$result = $amplitude->send($message);
```

### V2 API
```php
use Luur\Amplitude\AmplitudeV2;
use Luur\Amplitude\Event;

$amplitude = new AmplitudeV2('api-key');

$event = new Event();
$event->user_id = '123456';
$event->event_type = 'test-event';

$result = $amplitude->send($event);
```
