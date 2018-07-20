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

use Aesonus\Events\Contracts\ListenerInterface;
use Aesonus\Events\Exceptions\NoListenersException;

/**
 * Generic Implementation of the EventInterface
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Event implements Contracts\EventInterface
{
    use \Aesonus\Paladin\Traits\Validatable;
    
    //These help things look a bit more descriptive
    const LISTENER = 0;
    const PRIORITY = 1;

    /**
     * All the remaining listeners for this event
     * @var array 
     */
    protected $listenerQueue;
    
    /**
     * All the registered listeners for this event
     * @var array 
     */
    protected $listenerQueueBeforeDispatch;

    /**
     * 
     * @param ListenerInterface|array $listeners Array of ListenerInterfaces
     * or instance of ListenerInterface
     * @param int $priority higher priority is executed first
     * @throws \InvalidArgumentException Should throw on invalid $listener.
     * @return $this 
     */
    public function attach($listeners, int $priority = 0): Contracts\EventInterface
    {
        $this->v(__METHOD__, func_get_args());
        $queue_items = [];
        foreach ($listeners as $i => $listener) {
            if ($listener instanceof ListenerInterface) {
                $queue_items[] = [
                    static::LISTENER => $listener,
                    static::PRIORITY => $priority,
                ];
                continue;
            } elseif (!is_array($listener)) {
                //TODO make a better message
                $message = "Element at index $i in \$listeners is not a valid"
                    . " listener.";
                throw new \UnexpectedValueException($message);
            } elseif (count($listener) < 2 || !is_int($listener[self::PRIORITY])) {
                $listener[self::PRIORITY] = $priority;
            }
            $queue_items[] = $listener;
        }
        $this->listenerQueue = array_merge((array) $this->listenerQueue, $queue_items);
        usort($this->listenerQueue, function ($a, $b) {
            return $b[static::PRIORITY] - $a[static::PRIORITY];
        });
        return $this;
    }

    /**
     * Dispatches this event to all listener left in the queue
     * @throws NoListenersException Throws if no listeners attached
     * @throws ResumableException 
     * @return $this 
     */
    public function dispatch(): Contracts\EventInterface
    {
        if (!is_array($this->listenerQueue)) {
            throw new NoListenersException();
        }
        $this->listenerQueueBeforeDispatch = $this->listenerQueue;
        
        while ($listener = array_shift($this->listenerQueue)) {
            $listener[static::LISTENER]->handle($this);
        }
        return $this;
    }

    public function reset(): Contracts\EventInterface
    {
        $this->listenerQueue = $this->listenerQueueBeforeDispatch;
        return $this;
    }
}
