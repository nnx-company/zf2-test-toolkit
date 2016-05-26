# Тестирование приложений, использующих doctrine/doctrine-orm-module

Для тестирования приложений на zf2, использующих для работы с Doctrine2 doctrine/doctrine-orm-module, применяется
специальный класс \Nnx\ZF2TestToolkit\Listener\InitTestAppListener.

InitTestAppListener одновременно является как обработчиком событий PhpUnit, так и обработчиком событий приложения на zf2.

Данный класс позволяет конфигурировать настройки соедениния doctrine через конфиг phpunit.

# Алгоритм работы

При старте тестов в  конструктор InitTestAppListener из PhpUnit-движка передаются следующие параметры: 
connectionName, driverClass, params. Эти данные сохраняются в статических свойствах класса.

Далее при старте тестового приложения на zf2 при возникновении события onBootstrap InitTestAppListener объеденяет 
конфиг приложения с массивом вида:

```php
[
    'doctrine' => [
        'connection' => [
            $connectionName => [
                'driverClass' => $driverClass,
                'params' => $params
            ]
        ]
    ]
]
```

Где $connectionName, $driverClass, $params берутся из соответствующих настроек InitTestAppListener в phpunit.xml

# Пример использования

## Подключить InitTestAppListener в качестве обработчика событий PhpUnit'a

```xml
<?xml version="1.0"?>
<phpunit>
    <testsuites>
        <testsuite name="Test Suite">
            <directory>./test/phpunit/tests</directory>
        </testsuite>
    </testsuites>

    <filter>
        <blacklist>
            <directory>./vendor</directory>
            <directory>./test</directory>
        </blacklist>
    </filter>
    <logging>

    <listeners>
        <listener class="\Nnx\ZF2TestToolkit\Listener\InitTestAppListener">
            <arguments>
                <!-- Имя соеденения, которое можно получить через сервис doctrine.connection.ИМЯ_СОЕДЕНЕНИЯ -->
                <string>test</string>
                <!-- Используемый драйвер -->
                <string>\Doctrine\DBAL\Driver\PDOMySql\Driver</string>
                <array>
                    <element key="host">
                        <string>test_hosts</string>
                    </element>
                    <element key="port">
                        <string>test_port</string>
                    </element>
                    <element key="user">
                        <string>test_user</string>
                    </element>
                    <element key="password">
                        <string>test_password</string>
                    </element>
                    <element key="dbname">
                        <string>test_database_name</string>
                    </element>
                </array>

            </arguments>
        </listener>
    </listeners>

</phpunit>

```

Через конфиг PhpUnit'а можно передать следующие параметры:

Порядковый номер параметра |Имя           |Тип   |Описание
---------------------------|--------------|------|-------------------
1                          |connectionName|string|Имя соеденения doctrine (@see 'doctrine.connection.CONNECTION_NAME' в doctrine-orm-module )
2                          |driverClass   |string|Имя используемого драйвера (@see классы в \Doctrine\DBAL\Driver\)
3                          |params        |array |Параметры соеденения, специфичные для конкретной базы данных (host, port, user и т.д.)

Важно соблюдать порядок аргументов! 

## Подключить InitTestAppListener в качестве обработчика события для тестового приложения

Предположим, что есть тестовое приложение DefaultApp (ниже приведена структура каталогов проекта):

```txt
project
    config
    doc
    src
    test
        phpunit
        _files
            DefaultApp
                config
                    autoload
                        global.php

                application.config.php
        tests
            DefaultAppTest.php
        Bootstrap.php
    vendor
    phpunit.xml
```

Есть набор тестов, расположенных в файле DefaultAppTest.php. В этих тестах необходимо поднимать приложение на zf2 
с определенной конфигураций. Само приложение расположено в директории DefaultApp в папке _files. Предполагается, что 
тестовое приложение использует Doctrine. Тогда для того чтобы настройки соединения брались из phpunit.xml, необходимо
в application.config.php тестового приложения добавить InitTestAppListener:

```php

use Nnx\Doctrine\PhpUnit\TestData\TestPaths;
use Nnx\ZF2TestToolkit\Listener\InitTestAppListener;

return [
    'modules'                 => [
        'DoctrineModule',
        'DoctrineORMModule',
        'Nnx\\Doctrine'
    ],
    'module_listener_options' => [
        'module_paths'      => [
            'Nnx\\ModuleOptions' => TestPaths::getPathToModule(),
        ],
        'config_glob_paths' => [
            __DIR__ . '/config/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],
    'service_manager'         => [
        'invokables' => [
            InitTestAppListener::class => InitTestAppListener::class
        ]
    ],
    'listeners'               => [
        InitTestAppListener::class
    ]
];
```

InitTestAppListener необходимо:
- Зарегистрировать в ServiceLocator приложения (секция service_manager);
- Добавить его в обработчики события приложения (listeners).
