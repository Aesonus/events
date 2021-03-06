<?php
/*
 * This file is part of the events package
 * 
 *  (c) Cory Laughlin <corylcomposinger@gmail.com>
 * 
 * For full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Aesonus\Events;

use Aesonus\Events\Contracts\EventInterface;

/**
 * Generic implementation of DispatcherInterface
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Dispatcher implements Contracts\DispatcherInterface
{
    /**
     * Registered events
     * @var array 
     */
    protected $events = [];
    
    /**
     * Calls dispatch on registered event object
     * @param string $event class constant of EventInterface to fire
     * @return boolean 
     */
    public function dispatch($event): bool
    {
        if (array_key_exists($event, $this->events)) {
            $this->events[$event]->dispatch();
            return true;
        } else {
            return false;
        }
    }

    public function register($events): Contracts\DispatcherInterface
    {
        if ($events instanceof EventInterface) {
            $this->registerEvent($events);
        } elseif (is_array($events)) {
            array_walk($events, function ($event, $key) {
                $this->registerEvent($event);
            });
        } else {
            $this->throwInvalidArgumentException();
        }
        return $this;
    }
    
    protected function registerEvent($event)
    {
        
        if (!$event instanceof EventInterface) {
            $this->throwInvalidArgumentException();
        }
        $this->events[get_class($event)] = $event;
    }
    
    protected function throwInvalidArgumentException()
    {
        throw new \InvalidArgumentException("\$events must be instance of "
        . EventInterface::class . " or array of EventInterfaces.");
    }
}
