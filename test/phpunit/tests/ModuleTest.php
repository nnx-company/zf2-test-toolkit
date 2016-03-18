<?php
/**
 * @link     https://github.com/old-town/workflow-zf2-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\ZF2\Test\Toolkit\PhpUnit\Test;

use OldTown\Workflow\ZF2\Toolkit\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class ModuleTest
 *
 * @package OldTown\ZF2\Test\Toolkit\PhpUnit\Test
 */
class ModuleTest extends AbstractHttpControllerTestCase
{
    /**
     * Подготавливаем базу
     *
     */
    protected function setUp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultAppConfig()
        );

        parent::setUp();
    }


    /**
     *
     * @return void
     */
    public function testLoadModule()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getPathToDefaultAppConfig()
        );

        $this->assertModulesLoaded(['OldTown\\ZF2\\Test\\Toolkit']);
    }
}
