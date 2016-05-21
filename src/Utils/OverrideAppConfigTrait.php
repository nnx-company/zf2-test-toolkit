<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\Utils;

use Zend\ModuleManager\ModuleEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;


/**
 * Class OverrideAppConfigTrait
 *
 * @package Nnx\ZF2TestToolkit\Utils
 */
trait OverrideAppConfigTrait
{
    /**
     * ServiceListener
     *
     * @var ServiceListenerForTest
     */
    protected $serviceListenerForTest;

    /**
     * Конфиги для переопределения, конфига приложения
     *
     * @var array
     */
    protected $overrideConfigs = [];

    /**
     * Устанавливает конфиг приолжения
     *
     * @param $applicationConfig
     *
     * @return void
     */
    abstract public function setApplicationConfig($applicationConfig);

    /**
     * Возвращает конфиг приложения
     *
     * @return array
     */
    abstract public function getApplicationConfig();

    /**
     * Расширяет конфиг приложения
     *
     * @param array $newConfig
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\InvalidServiceNameException
     */
    public function overrideAppConfig(array $newConfig = [])
    {
        $applicationConfig = $this->getApplicationConfig();

        if (is_array($applicationConfig) && !isset($applicationConfig['service_manager']['factories']['ServiceListenerInterface'])) {
            $appConfig = ArrayUtils::merge($applicationConfig, [
                'service_manager' => [
                    'factories' => [
                        'ServiceListenerInterface' => [$this, 'serviceListenerInterfaceFactory'],
                    ]
                ]
            ]);

            $this->setApplicationConfig($appConfig);
        }

        $this->addOverrideConfig($newConfig);
    }


    /**
     * Метод фабрика, отвечающий за создание ServiceListener
     *
     * @param ServiceManager $sm
     *
     * @return ServiceListenerForTest
     */
    public function serviceListenerInterfaceFactory(ServiceManager $sm)
    {
        if ($this->serviceListenerForTest) {
            return $this->serviceListenerForTest;
        }

        $this->serviceListenerForTest = new ServiceListenerForTest($sm);
        $this->serviceListenerForTest->setOverrideAppConfigHandler([$this, 'overrideAppConfigHandler']);

        return $this->serviceListenerForTest;
    }

    /**
     * Добавляет конфиг, который будет переопределять, конфиг приложения
     *
     * @param array $overrideConfigs
     *
     * @return $this
     */
    protected function addOverrideConfig(array $overrideConfigs = [])
    {
        $this->overrideConfigs[] = $overrideConfigs;

        return $this;
    }

    /**
     * Наборк конфигов, которые заменяют конфиг приложения
     *
     * @return array
     */
    public function getOverrideConfigs()
    {
        return $this->overrideConfigs;
    }


    /**
     * Обработчик события загрузки всех модулей
     *
     * @param ModuleEvent $e
     */
    public function overrideAppConfigHandler(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $baseAppConfig         = $configListener->getMergedConfig(false);

        $overrideConfigs = $this->getOverrideConfigs();
        foreach ($overrideConfigs as $overrideConfig) {
            $baseAppConfig = ArrayUtils::merge($baseAppConfig, $overrideConfig);
        }

        $configListener->setMergedConfig($baseAppConfig);
    }
}
