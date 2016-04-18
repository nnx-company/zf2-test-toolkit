<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\Listener;

use Exception;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use PHPUnit_Framework_TestListener;

/**
 * Class InitTestAppListener
 *
 * @package Nnx\ZF2TestToolkit\Listener
 */
class InitTestAppListener extends AbstractListenerAggregate implements PHPUnit_Framework_TestListener
{
    /**
     * Ключем явялется имя соеденения, а значением массив, описывающий его характеристики
     *
     * @var array
     */
    protected static $connectionParams = [];

    /**
     * @inheritDoc
     */
    public function __construct($connectionName = null, $driverClass = null, array $params = [])
    {
        if (null !== $connectionName && !array_key_exists($connectionName, static::$connectionParams)) {
            static::$connectionParams[$connectionName] = [
                'connectionName' => $connectionName,
                'driverClass' => $driverClass,
                'params' => $params
            ];
        }
    }

    /**
     * Обнуляет стартовые параметры класса
     *
     * @return void
     */
    public static function reset()
    {
        static::$connectionParams = [];
    }

    /**
     * Подписываемся на событие приложения
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap']);
    }

    /**
     * @param MvcEvent $e
     * @throws \Zend\ServiceManager\ServiceLocatorInterface
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     */
    public function onBootstrap(MvcEvent $e)
    {
        /** @var ServiceManager $sm */
        $sm = $e->getApplication()->getServiceManager();
        $appConfig = $sm->get('Config');

        $newAppConfig = $appConfig;
        foreach (static::$connectionParams as $connectionName => $paramsItem) {
            $data = [
                'doctrine' => [
                    'connection' => [
                        $paramsItem['connectionName'] => [
                            'driverClass' => $paramsItem['driverClass'],
                            'params' => $paramsItem['params']
                        ]
                    ]
                ]
            ];
            $newAppConfig = ArrayUtils::merge($newAppConfig, $data);
        }

        $originalAllowOverride = $sm->getAllowOverride();
        $sm->setAllowOverride(true);
        $sm->setService('config', $newAppConfig);
        $sm->setAllowOverride($originalAllowOverride);
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test                 $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     * @param float                                  $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test $test
     * @param float                  $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
    }
}
