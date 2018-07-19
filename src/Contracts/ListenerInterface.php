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
interface ListenerInterface
{
    /**
     * Runs this code on certain events this object is attached to. May modify 
     * passed event.
     * @param EventInterface $event
     * @return void
     */
    public function handle(EventInterface $event): void;
}
