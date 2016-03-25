<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\PhpUnit\Test\Listener;

use PHPUnit_Framework_TestCase;
use Nnx\ZF2TestToolkit\Listener\InitTestAppListener;
use Zend\EventManager\EventManager;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;


/**
 * Class InitTestAppListenerTest
 *
 * @package Nnx\ZF2TestToolkit\PhpUnit\Test\Listener
 *
*  @backupStaticAttributes enabled
 */
class InitTestAppListenerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @inheritdoc
     * @return void
     */
    public function setUp()
    {
        InitTestAppListener::reset();
        parent::setUp();
    }

    /**
     * Проврека создания обработчика отвечающего за настройку конфигов из доктрины
     */
    public function testCreateInitTestAppListener()
    {
        $listener = new InitTestAppListener();

        static::assertInstanceOf(InitTestAppListener::class, $listener);
    }

    /**
     * Проврека подмены конфигов doctrine по событию приложения bootstrap
     *
     * @return void
     */
    public function testOnBootstrapHandler()
    {
        $expectedConnectionName = 'test_connection_name';
        $expectedDriverClass = 'test_driver_class_name';
        $expectedParams = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3'
        ];

        $eventManager = new EventManager();
        $listener = new InitTestAppListener($expectedConnectionName, $expectedDriverClass, $expectedParams);

        $listener->attach($eventManager);


        $mvcEvent = new MvcEvent();
        $mvcEvent->setName(MvcEvent::EVENT_BOOTSTRAP);
        $application = Application::init([
            'module_listener_options' => [],
            'modules' => []
        ]);
        $mvcEvent->setApplication($application);

        $eventManager->trigger($mvcEvent);

        $actualAppConfig = $application->getServiceManager()->get('config');

        $expectedAppConfig = [
            'doctrine' => [
                'connection' => [
                    $expectedConnectionName => [
                        'driverClass' => $expectedDriverClass,
                        'params' => $expectedParams
                    ]
                ]
            ]
        ];

        static::assertEquals($expectedAppConfig, $actualAppConfig);
    }
}
