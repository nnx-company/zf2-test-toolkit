<?php
/**
 * @link     https://github.com/old-town/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\ZF2\Test\Toolkit\PhpUnit\TestData;

/**
 * Class TestPaths
 *
 * @package OldTown\ZF2\Test\Toolkit\PhpUnit\TestData
 */
class TestPaths
{
    /**
     * Путь до дириктории с исходниками
     *
     * @return string
     */
    public static function getPathToSrc()
    {
        return include __DIR__ . '/../../../src';
    }

}
