<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Class StopDoctrineLoadCliPostEventListener
 *
 * @package Nnx\ZF2TestToolkit\Listener
 */
class StopDoctrineLoadCliPostEventListener extends AbstractListenerAggregate
{
    /**
     * Подписываемся на событие инициализирующее работу с консолью doctrine
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach('doctrine', 'loadCli.post', [$this, 'onDoctrineLoadCliPost'], 100);
    }

    /**
     * Останавливаем обработку событий
     *
     * @param EventInterface $event
     */
    public function onDoctrineLoadCliPost(EventInterface $event)
    {
        $event->stopPropagation(true);
    }
}
