<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\Utils;

use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\Listener\ServiceListener;
use Zend\ModuleManager\ModuleEvent;

/**
 * Class ServiceListenerForTest
 *
 * @package Nnx\ZF2TestToolkit\Utils
 */
class ServiceListenerForTest extends ServiceListener
{
    /**
     * Наборк хендлеров отвечающих за перегрузку конфигов
     *
     * @var callable
     */
    protected $overrideAppConfigHandler;

    /**
     * @param EventManagerInterface $events
     *
     * @return ServiceListener
     */
    public function attach(EventManagerInterface $events)
    {
        if (is_callable($this->overrideAppConfigHandler)) {
            $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, $this->overrideAppConfigHandler);
        }
        parent::attach($events);
    }

    /**
     * Добавляет хендлер для перегрузки конфигов
     *
     * @param callable $handler
     */
    public function setOverrideAppConfigHandler(callable $handler)
    {
        $this->overrideAppConfigHandler = $handler;
    }
}
