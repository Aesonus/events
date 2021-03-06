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

use Aesonus\Events\Event;

/**
 * Tests the generic Dispatcher
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class DispatcherTest extends \PHPUnit\Framework\TestCase
{

    protected $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new \Aesonus\Events\Dispatcher();
    }

    /**
     * @dataProvider registerDataProvider
     */
    public function testRegister($events)
    {
        $this->assertEquals($this->dispatcher, $this->dispatcher->register($events));
    }

    public function registerDataProvider()
    {
        return [
            'single event' => [
                new Event()
            ],
            'muliple_events' => [
                [new Event(), new Event(), new Event()]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider registerFailDataProvider
     */
    public function testRegisterFail($badEvents)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->dispatcher->register($badEvents);
    }

    public function registerFailDataProvider()
    {
        return [
            'ints' => [23, "42"],
            'strings' => ["doo", "poo"],
            'arrays' => [["icky", "array"], ["nasty" => 69, "alsoNasty" => 34]]
        ];
    }

    /**
     * @test
     */
    public function testDispatch()
    {
        $evfnc = function ($classname) {
            return $this->getMockBuilder(Event::class)->setMethods(['dispatch'])
                    ->setMockClassName($classname)->getMock();
        };
        $sdfkgjsdfkjgsdkghw = "MockEvent";
        $events[] = $evfnc($sdfkgjsdfkjgsdkghw);
        $events[0]->expects($this->once())->method('dispatch');
        $events[] = $evfnc("DontRunMe");
        $events[1]->expects($this->never())->method('dispatch');

        $this->dispatcher->register($events)->dispatch($sdfkgjsdfkjgsdkghw);
        $this->assertFalse($this->dispatcher->dispatch("NotFoundEvent"));
        $this->assertFalse((new \Aesonus\Events\Dispatcher())->dispatch("no go"));
    }
}
