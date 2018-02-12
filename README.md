# Events
A simple Event, Listener,and Dispatcher

#Tests
phpunit

#How to use

Create a dispatcher
```php
$dispatcher = new Dispatcher();
```

Extend the base event class and listener class for each of your events and listeners
```php
class MyEvent extends Event

class MyListener extends Listener
```

Override the handle method in listener to do a specific task
```php
public function handle(EventInterface $event)
```

Attach listeners to event(s)
```php
$event = new MyEvent();
$event->attach(new MyListener());
```

Register events with dispatcher
```php
$dispatcher->register($event);
```

Dispatch events with class name
```php
$dispatcher->dispatch(MyEvent::class);
```

[More to come]