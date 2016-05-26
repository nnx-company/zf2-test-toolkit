# Перегрузка конфигов приложения

В тестах часто возникает задача модифицировать конфиги приложения (имеется в виду, конфиг, полученный
после загрузки всех модулей). Для этих целей в тестах, основанных на \Zend\Test\PHPUnit\Controller\AbstractControllerTestCase,
можно применять специальный трейт \Nnx\ZF2TestToolkit\PhpUnit\Test\Utils\OverrideAppConfigTraitTest.

Пример использования:

```php
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

```
