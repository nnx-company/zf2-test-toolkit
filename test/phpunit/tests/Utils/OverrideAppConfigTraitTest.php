<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\PhpUnit\Test\Utils;

use Nnx\ZF2TestToolkit\PhpUnit\TestData\TestPaths;
use Nnx\ZF2TestToolkit\Utils\OverrideAppConfigTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use Zend\Stdlib\DispatchableInterface;

/**
 * Class OverrideAppConfigTraitTest
 *
 * @package Nnx\ZF2TestToolkit\PhpUnit\Test\Utils
 */
class OverrideAppConfigTraitTest  extends AbstractConsoleControllerTestCase
{
    use OverrideAppConfigTrait;


    /**
     * Проверка переопределения конфигов приложения
     *
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testOverrideAppConfig()
    {
        /** @noinspection PhpIncludeInspection */
        $applicationConfig = include TestPaths::getPathToTestOverrideConfigApp();

        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            $applicationConfig
        );

        $this->overrideAppConfig([
            'override_config_test_app' => [
                'param1' => 'myValue'
            ]
        ]);

        /** @var array $appConfig */
        $appConfig = $this->getApplicationServiceLocator()->get('config');

        static::assertEquals('myValue', $appConfig['override_config_test_app']['param1']);
    }


    /**
     * Проверка переопределения конфигов приложения
     *
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \PHPUnit_Framework_Exception
     */
    public function testOverridePluginManagerConfig()
    {
        /** @noinspection PhpIncludeInspection */
        $applicationConfig = include TestPaths::getPathToTestOverrideConfigApp();

        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            $applicationConfig
        );

        $this->overrideAppConfig([
            'override_config_test_app' => [
                'param1' => 'myValue'
            ]
        ]);

        $this->overrideAppConfig([
            'controllers' => [
                'services' => [
                    'myController' => $this->getMock(DispatchableInterface::class)
                ]
            ]
        ]);


        /** @var ServiceLocatorInterface $controllerLoader */
        $controllerLoader = $this->getApplicationServiceLocator()->get('ControllerLoader');

        static::assertTrue($controllerLoader->has('myController'));
    }


    /**
     * Проверка работы кеширования, для создания ServiceListener
     *
     */
    public function testCacheServiceListenerInterface()
    {
        /** @noinspection PhpIncludeInspection */
        $applicationConfig = include TestPaths::getPathToTestOverrideConfigApp();

        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            $applicationConfig
        );

        $sm = $this->getApplicationServiceLocator();

        /** @var OverrideAppConfigTrait $overrideAppConfigTraitMock */
        $overrideAppConfigTraitMock = $this->getMockForTrait(OverrideAppConfigTrait::class);

        $serviceListenerOriginal = $overrideAppConfigTraitMock->serviceListenerInterfaceFactory($sm);
        $serviceListenerCache = $overrideAppConfigTraitMock->serviceListenerInterfaceFactory($sm);

        static::assertSame($serviceListenerOriginal, $serviceListenerCache);
    }
}
