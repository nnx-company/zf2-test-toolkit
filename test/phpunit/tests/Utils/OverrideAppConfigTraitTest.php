<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\PhpUnit\Test\Utils;

use Nnx\ZF2TestToolkit\PhpUnit\TestData\TestPaths;
use Nnx\ZF2TestToolkit\Utils\OverrideAppConfigTrait;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;

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
}
