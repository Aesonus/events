<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
