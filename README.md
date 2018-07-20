[![Build Status](https://travis-ci.org/Aesonus/events.svg?branch=master)](https://travis-ci.org/Aesonus/events)

# Events
A simple Event, Listener,and Dispatcher

## Tests
phpunit

## How to use

Create a dispatcher
```php
$dispatcher = new Dispatcher();
```

Extend the base event class for each of your events: 
```php
class MyEvent extends Event { }
```

Implement listener interface for each of your events and listeners
```
class MyListener implements ListenerInterface {
    ...
```

Implement the handle method in listener to do a specific task:
```php
    ...
    public function handle(EventInterface $event): void { }
}
```

Attach listeners to event(s) in a queue:
```php
$event = new MyEvent();

$event->attach(new MyListener());
```

You can assign priorities to listeners as well. The default priority is 0.
```php
$event->attach(new MyListener(), 3);
```

You can attach multiple listeners using an array.
```php
$listeners = [new MyListener(),new MyListener(),new MyListener()];
$event->attach($listeners);
```

Each listener attached will be assigned the given priority:
```php
$listeners = [new MyListener(),new MyListener(),new MyListener()];

//They all get a priority of 3
$event->attach($listeners, 3);
```

Note that if the listeners all have the same priority, the queue will start at
the first element of the first attached listener(s). Consider the following code:
```php
$listenersA = [new MyListenerA(),new MyListenerB()];
$event->attach($listenersA);

$listenersB = [new MyListenerC(),new MyListenerD()];
$event->attach($listenersB);
```
When this event is dispatched, the first listener to receive the event will be MyListenerA.

You can also override the default priority by passing the listener in a numbered array
with the element at index 0 is the listener and the element at index 1 is the priority:
```php
$listeners = [new MyListener(),[new MyListener(), 2],new MyListener()];

//They all get a priority of 3, except for the the listener at index 1
$event->attach($listeners, 3);
```

Please note that all priorities must be integers. Priorities provided through the
array interface that are not integers will be set to the default priority.

Register events with dispatcher
```php
$dispatcher->register($event);
```

Dispatch events with class name. This will call the handle method on each listener
in the priority queue.
```php
$dispatcher->dispatch(MyEvent::class);
```

You can also dispatch an event without the help of a dispatcher:
```php
$event->dispatch();
```

The dispatch queue can be interrupted by an exception and resume where it left off.
```php
try {
    $event->dispatch(); // This will throw an exception from one of the listeners
} catch (ResumableException $ex) {
    //do stuff to make all better
}

//Resume the dispatch
$event->dispatch();
...
```

The dispatch queue may also be reset to the state it was in just before dispatch()
is called:
```php
$event->reset();
```

It is important to note that resuming execution of the event queue will change the
cached copy of the queue. Use resumability with care.