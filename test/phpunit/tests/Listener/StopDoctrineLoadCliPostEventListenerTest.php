<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\PhpUnit\Test\Listener;

use Nnx\ZF2TestToolkit\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;

/**
 * Class StopDoctrineLoadCliPostEventListenerTest
 *
 * @package Nnx\ZF2TestToolkit\PhpUnit\Test\Listener
 */
class StopDoctrineLoadCliPostEventListenerTest  extends AbstractConsoleControllerTestCase
{
    /**
     * При использование https://github.com/doctrine/DoctrineORMModule, при запуске приложения, происходит инициализация
     * компонента модуля отвечающего за работу с консолью. При этом происходит попытка создать сервис doctrine.entitymanager.orm_default.
     *
     * Неважно используются ли этот сервис или нет. Как следствие попытка запустить тесты, где нет настроенного соеденения для
     * doctrine.entitymanager.orm_default, и используютеся другой entitymanager, всегда заканчиваются исключением.
     *
     * Тест проверяет работу Listener'a отключающего регистарцию компонента работы с консолью модуля https://github.com/doctrine/DoctrineORMModule
     *
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     */
    public function testStopDoctrineLoadCliPostEvent()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToTestDoctrineCliApp()
        );

        $this->getApplication();
    }
}
