<?php
/**
 * @link  https://github.com/old-town/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\ZF2\Test\Toolkit;


use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;


/**
 * Class Module
 *
 * @package OldTown\Workflow\ZF2\Toolkit
 */
class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface
{

    /**
     * @param EventInterface $e
     *
     * @return array|void
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

    }


    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }

} 