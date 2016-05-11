<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

/**
 * Class CallbacksListenerAggregate
 *
 * @package Nnx\ZF2TestToolkit\Listener
 */
class CallbacksListenerAggregate extends AbstractListenerAggregate
{

    /**
     * Карта событий
     *
     * @var array
     */
    protected $eventMap = [];

    /**
     * CallbacksListenerAggregate constructor.
     *
     * @param array $eventMap
     */
    public function __construct(array $eventMap = [])
    {
        $this->eventMap = $eventMap;
    }


    /**
     * @param EventManagerInterface $events
     *
     * @throws \Nnx\ZF2TestToolkit\Listener\Exception\RuntimeException
     */
    public function attach(EventManagerInterface $events)
    {
        foreach ($this->eventMap as $eventName => $items) {
            if (!is_array($items)) {
                $errMsg = 'Handlers config not array';
                throw new Exception\RuntimeException($errMsg);
            }

            foreach ($items as $item) {
                if (!is_array($item)) {
                    $errMsg = 'Handler not array';
                    throw new Exception\RuntimeException($errMsg);
                }

                if (!array_key_exists('handler', $item)) {
                    $errMsg = 'Handler not found';
                    throw new Exception\RuntimeException($errMsg);
                }
                $handler = $item['handler'];

                $priority = 1;
                if (array_key_exists('priority', $item)) {
                    $priority = (integer)$priority;
                }

                $this->listeners[] = $events->attach($eventName, $handler, $priority);
            }
        }
    }
}
