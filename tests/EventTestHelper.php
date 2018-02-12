<?php
/*
 * This file is part of the events package
 * 
 *  (c) Cory Laughlin <corylcomposinger@gmail.com>
 * 
 * For full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
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
