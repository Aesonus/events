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
interface HandlerInterface
{
    public function handle(EventInterface $event);
}
