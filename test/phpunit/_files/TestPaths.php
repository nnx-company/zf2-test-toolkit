<?php
/**
 * @link    https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\PhpUnit\TestData;

/**
 * Class TestPaths
 *
 * @package Nnx\ZF2TestToolkit\PhpUnit\TestData
 */
class TestPaths
{
    /**
     * Путь до конфига приложения по умолчанию
     *
     * @return string
     */
    public static function getPathToTestDoctrineCliApp()
    {
        return  __DIR__ . '/../_files/DoctrineCliApp/application.config.php';
    }
}
