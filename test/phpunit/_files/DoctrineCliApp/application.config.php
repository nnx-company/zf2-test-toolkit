<?php

use Nnx\ZF2TestToolkit\Listener\StopDoctrineLoadCliPostEventListener;

return [
    'modules'                 => [
        'DoctrineModule',
        'DoctrineORMModule'
    ],
    'module_listener_options' => [

    ],
    'service_manager'         => [
        'invokables' => [
            StopDoctrineLoadCliPostEventListener::class => StopDoctrineLoadCliPostEventListener::class
        ]
    ],
    'listeners'               => [
        StopDoctrineLoadCliPostEventListener::class
    ]
];
