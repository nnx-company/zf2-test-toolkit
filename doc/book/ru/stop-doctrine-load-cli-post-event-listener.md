# Блокировка инициализации функционала работы с консолью DoctrineORMModule

Модуль  [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) предоставляет из себя интеграционное решение 
позволяющее удобно работать с Doctrine2 в приложениях на ZendFramework2.

[DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) предоставляет набор консольных комманд для работы
с функционалом Doctrine2.

Работа с консолью в [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) выполненна таким образом, что
если приложение запускается из консоли, то происходит автоматическое получение сервиса doctrine.entitymanager.orm_default.
В случае если для этого сервиса нет корректно настроенного соеденения приложение падает с исключением.

Таким образом если запустить из под консоли приложение которое работает с модулем [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule),
то в случае отсутствия корректно работающего соденения с базой данных, для  doctrine.entitymanager.orm_default приложение работать не будет.

В случа написания тестов возникает проблема, когда используется менеджер сущностей Doctrine2 отличный от orm_default.
Для решения проблемы можно воспользоваться \Nnx\ZF2TestToolkit\Listener\StopDoctrineLoadCliPostEventListener который отключает,
инициализацию работы с консолью у модуля [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule).

Пример использования:

Необходимо в конфиг тестового приложения (application.config.php) по аналогии добавить следующии изменения.

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
