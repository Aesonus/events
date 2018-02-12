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

/**
 * A Generic implementation of the ListenerInterface
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Listener implements Contracts\ListenerInterface
{
    public function handle(Contracts\EventInterface $event)
    {
        
    }
}
