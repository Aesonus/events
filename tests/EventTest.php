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
use Aesonus\Events\Listener;
use Aesonus\Events\Exceptions\ResumableException;

/**
 * Description of EventTest
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class EventTest extends \Aesonus\TestLib\BaseTestCase
{

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject 
     */
    protected $testObj;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected $mockEventBuilder;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected $mockListenerBuilder;

    public function setUp()
    {
        $this->mockEventBuilder = $this->getMockBuilder(Event::class);
        $this->testObj = $this->mockEventBuilder->setMethods()->getMock();
        $this->mockListenerBuilder = $this->mockListenerBuilder();
    }

    private function mockListenerBuilder()
    {
        return $this->getMockBuilder(Listener::class);
    }

    private function getThreeMockListeners()
    {
        $listener[0] = $this->mockListenerBuilder()->getMock();
        $listener[1] = $this->mockListenerBuilder()->getMock();
        $listener[2] = $this->mockListenerBuilder()->getMock();
        return $listener;
    }

    /**
     * @test
     * @dataProvider attachMethodThrowsExceptionIfListenersParamsInvalidDataProvider
     */
    public function attachMethodThrowsExceptionIfListenersParamsInvalid($listeners, $priority)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->testObj->attach($listeners, $priority);
    }

    /**
     * DataProvider
     */
    public function attachMethodThrowsExceptionIfListenersParamsInvalidDataProvider()
    {
        return [
            [42, 2],
            ['string', 2],
            [new \stdClass(), 5] // No one wants an std
        ];
    }

    /**
     * @test
     * @dataProvider attachMethodQueueListenersInOrderOfPriorityDataProvider
     */
    public function attachMethodQueueListenersInOrderOfPriority
        ($listeners, $default_priority, $expected)
    {
        $this->invokeMethod($this->testObj, 'attach', [$listeners, $default_priority]);
        $actual = $this->getPropertyValue($this->testObj, 'listenerQueue');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function attachMethodQueueListenersInOrderOfPriorityDataProvider()
    {
        $listener = $this->getThreeMockListeners();
        return [
            '3 listeners with listener 1 using default priority' => [[
                [$listener[0], 3],
                [$listener[2], 13],
                $listener[1],
                ], 0, [
                    [$listener[1], 0],
                    [$listener[0], 3],
                    [$listener[2], 13],
                ]],
        ];
    }

    /**
     * @test
     * @dataProvider attachQueuesListenersInOrderOfPriorityWithExistingListenersDataProvider
     */
    public function attachQueuesListenersInOrderOfPriorityWithExistingListeners
        ($listeners, $existing_listeners, $expected)
    {
        $this->setPropertyValue($this->testObj, 'listenerQueue', $existing_listeners);
        $this->invokeMethod($this->testObj, 'attach', [$listeners, 0]);
        $actual = $this->getPropertyValue($this->testObj, 'listenerQueue');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function attachQueuesListenersInOrderOfPriorityWithExistingListenersDataProvider()
    {
        $listener = $this->getThreeMockListeners();
        return [
            '1 listener with 2 prexisting' => [
                [
                    $listener[1]
                ], [
                    [$listener[0], 3],
                    [$listener[2], 13],
                ], [
                    [$listener[1], 0],
                    [$listener[0], 3],
                    [$listener[2], 13],
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider attachQueuesListenersDataProvider
     */
    public function attachQueuesListeners($listeners, $default_priority, $expected)
    {
        $this->invokeMethod($this->testObj, 'attach', [$listeners, $default_priority]);
        $actual = $this->getPropertyValue($this->testObj, 'listenerQueue');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function attachQueuesListenersDataProvider()
    {
        $listener = $this->getThreeMockListeners();
        return [
            '1 listener using default priority' => [[$listener[0]], 3, [
                    [$listener[0], 3]
                ]],
            '3 listeners using default priority' => [$listener, 3, [
                    [$listener[0], 3],
                    [$listener[1], 3],
                    [$listener[2], 3],
                ]],
            '1 listener' => [[
                [$listener[0], 3],
                ], 0, [
                    [$listener[0], 3],
                ]],
            '2 listeners' => [[
                [$listener[0], 3],
                [$listener[1], 3],
                ], 0, [
                    [$listener[0], 3],
                    [$listener[1], 3],
                ]],
        ];
    }

    /**
     * @test
     * @dataProvider attachMethodThrowsExceptionOnInvalidListenerElementDataProvider
     */
    public function attachMethodThrowsExceptionOnInvalidListenerElement($invalidListener)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->invokeMethod($this->testObj, 'attach', [
            $invalidListener, 0
        ]);
    }

    /**
     * Data Provider
     */
    public function attachMethodThrowsExceptionOnInvalidListenerElementDataProvider()
    {
        return [
            [[3]],
            [[new \stdClass()]],
            [['sorry, no good']]
        ];
    }

    /**
     * @test
     * @dataProvider attachMethodUsesDefaultPriorityOnNonIntPriorityDataProvider
     */
    public function attachMethodUsesDefaultPriorityOnNonIntPriority
        ($listeners, $default_priority, $expected)
    {
        $this->invokeMethod($this->testObj, 'attach', [$listeners, $default_priority]);
        $actual = $this->getPropertyValue($this->testObj, 'listenerQueue');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function attachMethodUsesDefaultPriorityOnNonIntPriorityDataProvider()
    {
        $listeners = $this->getThreeMockListeners();
        return [
            '1 listener' => [[
                [$listeners[0], 'cant use this'],
                ], 0, [
                    [$listeners[0], 0],
                ]],
            '2 listeners' => [[
                [$listeners[0], new \stdClass()], //No one wants an std
                [$listeners[1], 3],
                ], 0, [
                    [$listeners[0], 0],
                    [$listeners[1], 3],
                ]],
        ];
    }

    /**
     * @test
     * @dataProvider attachMethodUsesDefaultPriorityIfPriorityKeyDoesntExistDataProvider
     */
    public function attachMethodUsesDefaultPriorityIfPriorityKeyDoesntExist
        ($listeners, $default_priority, $expected)
    {
        $this->invokeMethod($this->testObj, 'attach', [$listeners, $default_priority]);
        $actual = $this->getPropertyValue($this->testObj, 'listenerQueue');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function attachMethodUsesDefaultPriorityIfPriorityKeyDoesntExistDataProvider()
    {
        $listeners = $this->getThreeMockListeners();
        return [
            '1 listener' => [[
                [$listeners[0]],
                ], 0, [
                    [$listeners[0], 0],
                ]],
            '2 listeners' => [[
                [$listeners[0]],
                [$listeners[1]],
                ], 3, [
                    [$listeners[0], 3],
                    [$listeners[1], 3],
                ]],
        ];
    }

    /**
     * @test
     */
    public function attachMethodIsFluent()
    {
        $listeners = $this->getThreeMockListeners();

        $expected = $this->testObj;
        $actual = $this->invokeMethod($this->testObj, 'attach', [
            $listeners, 0
        ]);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function dispatchMethodCanBeInterruptedAndResumed()
    {
        $listeners = $this->getThreeMockListeners();

        $listeners[0]->expects($this->once())->method('handle');
        $listeners[2]->expects($this->once())->method('handle');
        $listeners[1]->expects($this->once())->method('handle')
            ->willThrowException(new ResumableException());
        $this->testObj->attach($listeners);
        try {
            $this->testObj->dispatch();
        } catch (ResumableException $e) {
            
        }
        $this->testObj->dispatch();
    }

    /**
     * @test
     */
    public function dispatchMethodIsFluent()
    {
        $listeners = $this->getThreeMockListeners();
        $this->testObj->attach($listeners);
        $this->assertSame($this->testObj, $this->testObj->dispatch());
    }
}
