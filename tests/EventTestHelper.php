<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aesonus\Events\Tests;

/**
 * Description of EventTestHelper
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class EventTestHelper extends \Aesonus\Events\Event
{
    public function __get($name)
    {
        if ($name == 'listeners') {
            return $this->listeners;
        }
    }
}
