<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\PhpUnit\Test\Listener;

use PHPUnit_Framework_TestCase;
use Nnx\ZF2TestToolkit\Listener\CallbacksListenerAggregate;
use Zend\EventManager\EventManager;

/**
 * Class CallbacksListenerAggregateTest
 *
 * @package Nnx\ZF2TestToolkit\PhpUnit\Test\Listener
 */
class CallbacksListenerAggregateTest  extends PHPUnit_Framework_TestCase
{
    /**
     * Проверка ситуации когда в карте событий, указано имя события, но не задан конфиг описывающий обработчики
     *
     * @expectedException \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Handlers config not array
     *
     * @throws \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     */
    public function testHandlersConfigNotArray()
    {
        $eventMap = [
            'testEvent' => 'invalidConfig'
        ];
        $callbacksListenerAggregate = new CallbacksListenerAggregate($eventMap);

        $eventManager = new EventManager();

        $callbacksListenerAggregate->attach($eventManager);
    }

    /**
     * Проверка ситуации когда в карте событий, указано имя события, но среди конфигов обработчиков, передан конфиг, не
     * являющиейся массивом
     *
     * @expectedException \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Handler not array
     *
     * @throws \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     */
    public function testHandlerNotArray()
    {
        $eventMap = [
            'testEvent' => [
                'invalidItemConfig'
            ]
        ];
        $callbacksListenerAggregate = new CallbacksListenerAggregate($eventMap);

        $eventManager = new EventManager();

        $callbacksListenerAggregate->attach($eventManager);
    }


    /**
     * Проверка ситуации когда в карте событий, указано имя события, но среди конфигов обработчиков, передан конфиг, в
     * котором не задан handler
     *
     * @expectedException \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     * @expectedExceptionMessage Handler not found
     *
     * @throws \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     */
    public function testHandlerNoFound()
    {
        $eventMap = [
            'testEvent' => [
                [

                ]
            ]
        ];
        $callbacksListenerAggregate = new CallbacksListenerAggregate($eventMap);

        $eventManager = new EventManager();

        $callbacksListenerAggregate->attach($eventManager);
    }


    /**
     * Проверка добавления обработчиков
     *
     *
     * @throws \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     */
    public function testAddHandlers()
    {
        $handlerCompleted = false;

        $eventMap = [
            'testEvent' => [
                [
                    'handler' => function () use (&$handlerCompleted) {
                        $handlerCompleted = true;
                    },
                    'priority' => 5
                ]
            ]
        ];
        $callbacksListenerAggregate = new CallbacksListenerAggregate($eventMap);

        $eventManager = new EventManager();

        $callbacksListenerAggregate->attach($eventManager);

        $eventManager->trigger('testEvent');

        static::assertTrue($handlerCompleted);
    }
}
