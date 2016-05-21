<?php

return [
    'modules'                 => [

    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ]
];