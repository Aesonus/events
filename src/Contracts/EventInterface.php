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
     * @param ListenerInterface|array $listeners Must be array of ListenerInter-
     * faces
     * or instance of ListenerInterface
     * @throws \InvalidArgumentException Should throw on invalid $listener.
     * @return $this Must be able to chain
     */
    public function attach($listeners);
    
    /**
     * Calls the handle method on all attached ListenerInterfaces
     * @throws NoListenersException Should throw if no listeners added
     * @return EventInterface Returns the event that was passed through all 
     * listeners registered
     */
    public function dispatch();
}
