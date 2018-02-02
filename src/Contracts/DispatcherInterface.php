<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aesonus\Events\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface DispatcherInterface
{
    /**
     * Attaches a handler to a specific event
     * @param \Aesonus\Events\Contracts\HandlerInterface $handler Handler for the event
     * @param string $event class constant of the desired event
     * @param int $priority Handler that should be fired first higher = more priority
     */
    public function attachHandler(HandlerInterface $handler, $event, $priority = 10);
    
    /**
     * Fires an event
     * @param \Aesonus\Events\Contracts\EventInterface $event
     */
    public function event(EventInterface $event);
}
