<?php
/**
 * @link     https://github.com/old-town/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\ZF2\Test\Toolkit\PhpUnit\Test\Listener;

use PHPUnit_Framework_TestCase;
use OldTown\ZF2\Test\Toolkit\Listener\InitTestAppListener;


/**
 * Class InitTestAppListenerTest
 *
 * @package OldTown\ZF2\Test\Toolkit\PhpUnit\Test\Listener
 */
class InitTestAppListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * проверка создания обработчкика, для подмены данных doctrine
     */
    public function testCreateInitTestAppListener()
    {
        $actual = new InitTestAppListener();

        static::assertInstanceOf(InitTestAppListener::class, $actual);
    }
}
