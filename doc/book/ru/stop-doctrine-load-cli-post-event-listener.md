# Блокировка инициализации функционала работы с консолью DoctrineORMModule

Модуль  [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) представляет из себя интеграционное решение, 
позволяющее удобно работать с Doctrine2 в приложениях на ZendFramework2.

[DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) предоставляет набор консольных команд для работы
с функционалом Doctrine2.

Работа с консолью в [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) выполнена таким образом, что
если приложение запускается из консоли, то происходит автоматическое получение сервиса doctrine.entitymanager.orm_default.
В случае если для этого сервиса нет корректно настроенного соединения, приложение падает с исключением.

Таким образом, если запустить из-под консоли приложение, которое работает с модулем [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule),
то в случае отсутствия корректно работающего соединения с базой данных для doctrine.entitymanager.orm_default приложение работать не будет.

В случае написания тестов возникает проблема, когда используется менеджер сущностей Doctrine2, отличный от orm_default.
Для решения проблемы можно воспользоваться \Nnx\ZF2TestToolkit\Listener\StopDoctrineLoadCliPostEventListener, который отключает
инициализацию работы с консолью у модуля [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule).

Пример использования:

Необходимо в конфиг тестового приложения (application.config.php) по аналогии добавить следующие изменения.

```php
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

```
