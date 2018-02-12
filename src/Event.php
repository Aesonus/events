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

    /**
     * All the registered listeners for this event
     * @var array 
     */
    protected $listeners;


    /**
     * {@inheritdoc}
     * @param ListenerInterface|array $listeners Array of ListenerInterfaces
     * or instance of ListenerInterface
     * @param int|null $priority higher priority is executed first
     * @throws \InvalidArgumentException Should throw on invalid $listener.
     * @return $this 
     */
    public function attach($listeners)
    {
        if ($listeners instanceof ListenerInterface) {
            $this->attachListeners([$listeners]);
        } elseif (is_array($listeners)) {
            $this->attachListeners($listeners);
        } else {
            $this->throwInvalidArgumentException();
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws NoListenersException Throws if no listeners attached
     * @return $this 
     */
    public function dispatch()
    {
        if (!is_array($this->listeners)) {
            throw new NoListenersException();
        }
        //Working copy
        $listeners = $this->listeners;

        while ($listener = array_shift($listeners)) {
            $listener->handle($this);
        }

    }

    /**
     * Override to customize the error message
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function throwInvalidArgumentException()
    {
        throw new \InvalidArgumentException("\$listeners must be instance of "
        . ListenerInterface::class . " or array of ListenerInterfaces.");
    }

    /**
     * 
     * @param array $listeners
     * @return $this
     */
    protected function attachListeners(array $listeners)
    {
        foreach ($listeners as $listener) {
            if (!$listener instanceof ListenerInterface) {
                $this->throwInvalidArgumentException();
            }
            $this->listeners[] = $listener;
        }
        return $this;
    }
}
