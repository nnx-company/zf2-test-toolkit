<?php
/**
 * @link     https://github.com/nnx-framework/zf2-test-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\ZF2TestToolkit\Utils;

use Nnx\ZF2TestToolkit\Listener\CallbacksListenerAggregate;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use Ramsey\Uuid\Uuid;

/**
 * Class OverrideAppConfigTrait
 *
 * @package Nnx\ZF2TestToolkit\Utils
 */
trait OverrideAppConfigTrait
{
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

        if (is_array($applicationConfig)) {
            $listenerName = 'bootstrapAppHandler_' . Uuid::uuid4()->toString();

            $appConfig = ArrayUtils::merge($applicationConfig, [
                'listeners' => [
                    $listenerName
                ],
                'service_manager' => [
                    'services' => [
                        $listenerName => new CallbacksListenerAggregate([
                            MvcEvent::EVENT_BOOTSTRAP => [
                                [
                                    'handler' => function (MvcEvent $e) use ($newConfig) {
                                        /** @var ServiceManager $sm */
                                        $sm = $e->getApplication()->getServiceManager();
                                        $baseAppConfig = $sm->get('Config');

                                        $appConfig = ArrayUtils::merge($baseAppConfig, $newConfig);


                                        $originalAllowOverride = $sm->getAllowOverride();
                                        $sm->setAllowOverride(true);
                                        $sm->setService('config', $appConfig);
                                        $sm->setAllowOverride($originalAllowOverride);

                                    }
                                ]
                            ]
                        ])
                    ]
                ]
            ]);

            $this->setApplicationConfig($appConfig);
        }
    }
}
