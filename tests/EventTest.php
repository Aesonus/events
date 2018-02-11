<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aesonus\Events\Tests;

/**
 * Description of EventTest
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class EventTest extends \PHPUnit\Framework\TestCase
{

    protected $event;
    protected $listeners;

    public function setUp()
    {
        $this->event = new \Aesonus\Events\Event();
        $this->listeners = [
            new \Aesonus\Events\Listener(),
            new \Aesonus\Events\Listener(),
            new \Aesonus\Events\Listener(),
        ];
    }
    
    public function testGet()
    {
        
    }

    public function testAttachSingleListener()
    {
        foreach ($this->listeners as $listener) {
            $this->assertEquals($this->event, $this->event->attach($listener));
        }
    }

    public function testAttachListenerArray()
    {

        $this->assertEquals($this->event, $this->event->attach($this->listeners));
    }

    /**
     * 
     * @dataProvider invalidArgumentDataProvider
     */
    public function testInvalidArgumentException($listener)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->event->attach($listener);
    }

    public function invalidArgumentDataProvider()
    {
        return [
            'array of listeners' => [[new \Aesonus\Events\Listener(), "invalid"]],
            'illegal argument' => ["bad_arg"],
            'integer' => [4]
        ];
    }

    public function testDispatch()
    {
        $mockListeners = [
            $this->getMockBuilder(\Aesonus\Events\Listener::class)->setMethods(['handle'])->getMock(),
            $this->getMockBuilder(\Aesonus\Events\Listener::class)->setMethods(['handle'])->getMock(),
            $this->getMockBuilder(\Aesonus\Events\Listener::class)->setMethods(['handle'])->getMock(),
        ];
        /**
         * @param Mock $name Description
         */
        array_walk($mockListeners, function ($listener, $index) {
            $listener->expects($this->once())->method('handle')->
                with($this->equalTo($this->event));
        });
        $this->event->attach($mockListeners);
        $this->event->dispatch();
        $this->expectException(\Aesonus\Events\Exceptions\NoListenersException::class);
        (new \Aesonus\Events\Event())->dispatch();
    }
}
