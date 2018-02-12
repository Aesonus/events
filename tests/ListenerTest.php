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
 * Tests the listener class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class ListenerTest extends \PHPUnit\Framework\TestCase
{
    protected $listener;
    protected $event;
        
    public function setUp()
    {
        $this->listener = new \Aesonus\Events\Listener();
        $this->event = new \Aesonus\Events\Event();
    }
    
    public function testListenerHandle()
    {
        $this->assertNull($this->listener->handle($this->event));
    }
}
