<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aesonus\Events\Contracts;

/**
 * Should call the dispatch method on all registered events
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface DispatcherInterface
{
    /**
     * Should call dispatch on registered event objects
     * @param string $event class constant of EventInterface to fire
     * @return bool Should return if the event was fired
     */
    public function dispatch($event): bool;
    
    /**
     * Adds events to registered events
     * @param EventInterface|array $events Must be array of EventInterfaces
     * or instance of EventInterface
     * @throws \InvalidArgumentException Should throw on invalid $events.
     * @return $this MUST be fluent
     */
    public function register($events): DispatcherInterface;
}
