# **mezzio-simple-throttle**

`Ryudith\MezzioSimpleThrottle` is middleware library for Mezzio framework.

## **Instalation**

To install run the following command :

```bash
$ composer require ryudith/mezzio-simple-throttle
```


## **Usage**

First add `ConfigProvider.php` to `config/config.php`

```php

...

$aggregator = new ConfigAggregator([
    ...

    \Ryudith\MezzioSimpleThrottle\ConfigProvider::class,  // <= add this line
    
    ...

    class_exists(\Mezzio\Swoole\ConfigProvider::class)
        ? \Mezzio\Swoole\ConfigProvider::class
        : function (): array {
            return [];
        },
    ...
], $cacheConfig['config_cache_path']);

...

```

Then register middleware by add `SimpleThrottle::class` to `config/pipeline.php`

```php

...

use Ryudith\MezzioSimpleThrottle\SimpleThrottle;

...

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    // The error handler should be the first (most outer) middleware to catch
    // all Exceptions.
    $app->pipe(ErrorHandler::class);
    $app->pipe(ServerUrlMiddleware::class);
    $app->pipe(SimpleThrottle::class);  // <= add this line

    ...

};

...

```

> You can add `$app->pipe(SimpleThrottle::class)` before `$app->pipe(ErrorHandler::class)` if you want.


### Custom configuration


Configuration for middleware is locate in `vendor/ryudith/mezzio-simple-throttle/ConfigProvider.php` which the important content is :


```php
return [
    'dependencies' => [
        'factories' => [
            FileSystemThrottleStorage::class => FileSystemThrottleStorageFactory::class,
            ThrottleResponse::class => ThrottleResponseFactory::class,
        ],
    ],
    'mezzio_simple_throttle' => [
        'request_limit_per_minute' => 10,
        'request_real_ip_key' => 'REMOTE_ADDR',  // key for $_ENV or $_SERVER to get request real ip
        'ip_path_key' => true,  // data key based IP and URI path or IP only data key
        'throttle_data_dir' => './data/throttle',
        'file_data_delimiter' => '||',
        'throttle_storage_class' => FileSystemThrottleStorage::class,
        'throttle_response_class' => ThrottleResponse::class,
    ],
];
```

Detail :

1. `request_limit_per_minute` is how many hit before trigger throttle
2. `request_real_ip_key` is assoc key to get request IP, default is 'REMOTE_ADDR' which you can change if you have custom key on your webserver.
3. `ip_path_key` is flag to generate key if use IP-Path combine or just IP address.
4. `throttle_data_dir` is string path location to save throttle record data.
5. `file_data_delimiter` is data delimiter inside file, since this library file based record data.
6. `throttle_storage_class` is service key to save throttle data. You can change if you want another storage type (do not forget implement interface `Ryudith\MezzioSimpleThrottle\Storage\StorageInterface` on your custom class)
7. `throttle_response_class` is class to give response when throttle hit limit. Also you can change this if you want use your own class (do not forget implement interface `Ryudith\MezzioSimpleThrottle\Response\ThrottleResponseInterface` on your own class).

You do not have to edit configuration inside `ConfigProvider.php` directly, just add configuration you want to change to `config/autoload/mezzio.global.php` or any configuration file you use.

For example you can add your custom configuration like this inside `config/autoload/mezzio.global.php` :

```php

...

return [
    // Toggle the configuration cache. Set this to boolean false, or remove the
    // directive, to disable configuration caching. Toggling development mode
    // will also disable it by default; clear the configuration cache using
    // `composer clear-config-cache`.
    ConfigAggregator::ENABLE_CACHE => true,

    // Enable debugging; typically used to provide debugging information within templates.
    'debug'  => true,
    'mezzio' => [
        // Provide templates for the error handling middleware to use when
        // generating responses.
        'error_handler' => [
            'template_404'   => 'error::4042',
            'template_error' => 'error::error',
        ],
    ],
    // add only configuration you want to change
    'mezzio_simple_throttle' => [
        'ip_path_key' => false,
        'file_data_delimiter' => '//'
    ],
];

...

```


## **Documentation**

[API Documentation](https://github.com/ryudith/mezzio-simple-throttle/tree/master/docs/api/classes)

[Issues or questions](https://github.com/ryudith/mezzio-simple-throttle/issues)


## Next
- [] Add exclude path and/or IP