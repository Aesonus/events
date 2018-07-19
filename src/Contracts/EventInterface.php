<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aesonus\Events\Contracts;

use Aesonus\Events\Exceptions\NoListenersException;

/**
 * 
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface EventInterface
{
    /**
     * Adds listeners to the event queue
     * @param ListenerInterface|array $listeners Must be array of ListenerInterfaces
     * or instance of ListenerInterface
     * @param int $priority The priority of the listener. The higher, the more important.
     * MUST set the priority if not given
     * @throws \InvalidArgumentException Should throw on invalid $listener.
     * @return $this Must be fluent
     */
    public function attach($listeners, int $priority = 0): EventInterface;
    
    /**
     * MUST call the handle method on all attached ListenerInterfaces. MUST allow 
     * the listener queue to be resumed if it was previously interrupted by an exception.
     * @throws NoListenersException MUST throw if no listeners added
     * @return EventInterface Returns the event that was passed through all 
     * listeners registered.
     */
    public function dispatch(): EventInterface;
}
