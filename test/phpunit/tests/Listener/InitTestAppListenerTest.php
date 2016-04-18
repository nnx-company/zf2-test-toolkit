<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\PhpUnit\Test\Listener;

use PHPUnit_Framework_TestCase;
use Nnx\ZF2TestToolkit\Listener\InitTestAppListener;
use Zend\Mvc\Application;
use PHPUnit_Framework_Test;
use Exception;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_TestSuite;

/**
 * Class InitTestAppListenerTest
 *
 * @package Nnx\ZF2TestToolkit\PhpUnit\Test\Listener
 *
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
     * @throws \Zend\EventManager\Exception\InvalidCallbackException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testOnBootstrapHandler()
    {
        $expectedConnectionName1 = 'test_connection_name1';
        $expectedDriverClass1 = 'test_driver_class_name1';
        $expectedParams1 = [
            'key1_1' => 'value1_1',
            'key1_2' => 'value1_2',
            'key1_3' => 'value1_3'
        ];
        new InitTestAppListener($expectedConnectionName1, $expectedDriverClass1, $expectedParams1);

        $expectedConnectionName2 = 'test_connection_name2';
        $expectedDriverClass2 = 'test_driver_class_name2';
        $expectedParams2 = [
            'key2_1' => 'value2_1',
            'key2_2' => 'value2_2',
            'key2_3' => 'value2_3'
        ];
        new InitTestAppListener($expectedConnectionName2, $expectedDriverClass2, $expectedParams2);



        $application = Application::init([
            'module_listener_options' => [],
            'modules' => [],
            'service_manager' => [
                'invokables' => [
                    InitTestAppListener::class => InitTestAppListener::class
                ]
            ],
            'listeners'               => [
                InitTestAppListener::class
            ]
        ]);

        $actualAppConfig = $application->getServiceManager()->get('config');

        $expectedAppConfig = [
            'doctrine' => [
                'connection' => [
                    $expectedConnectionName1 => [
                        'driverClass' => $expectedDriverClass1,
                        'params' => $expectedParams1
                    ],
                    $expectedConnectionName2 => [
                        'driverClass' => $expectedDriverClass2,
                        'params' => $expectedParams2
                    ],
                ]
            ]
        ];

        static::assertEquals($expectedAppConfig, $actualAppConfig);
    }


    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testAddError()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_Test $test */
        $test = $this->getMock(PHPUnit_Framework_Test::class);
        /** @var Exception $e */
        $e = $this->getMock(Exception::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->addError($test, $e, 0));
    }

    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testAddFailure()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_Test $test */
        $test = $this->getMock(PHPUnit_Framework_Test::class);
        /** @var PHPUnit_Framework_AssertionFailedError $e */
        $e = $this->getMock(PHPUnit_Framework_AssertionFailedError::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->addFailure($test, $e, 0));
    }

    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testAddIncompleteTest()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_Test $test */
        $test = $this->getMock(PHPUnit_Framework_Test::class);
        /** @var Exception $e */
        $e = $this->getMock(Exception::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->addIncompleteTest($test, $e, 0));
    }

    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testAddRiskyTest()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_Test $test */
        $test = $this->getMock(PHPUnit_Framework_Test::class);
        /** @var Exception $e */
        $e = $this->getMock(Exception::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->addRiskyTest($test, $e, 0));
    }

    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testAddSkippedTest()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_Test $test */
        $test = $this->getMock(PHPUnit_Framework_Test::class);
        /** @var Exception $e */
        $e = $this->getMock(Exception::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->addSkippedTest($test, $e, 0));
    }

    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testStartTestSuite()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_TestSuite $suite */
        $suite = $this->getMock(PHPUnit_Framework_TestSuite::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->startTestSuite($suite));
    }

    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testEndTestSuite()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_TestSuite $suite */
        $suite = $this->getMock(PHPUnit_Framework_TestSuite::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->endTestSuite($suite));
    }

    /**
     * @inheritdoc
     *
     * @param PHPUnit_Framework_Test $test
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testStartTest()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_Test $test */
        $test = $this->getMock(PHPUnit_Framework_Test::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->startTest($test));
    }

    /**
     * @inheritdoc
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testEndTest()
    {
        $listener = new InitTestAppListener();
        /** @var PHPUnit_Framework_Test $test */
        $test = $this->getMock(PHPUnit_Framework_Test::class);

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        static::assertNull($listener->endTest($test, 0));
    }
}
