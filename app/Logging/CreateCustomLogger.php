<?php

namespace App\Logging;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CreateCustomLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $level = data_get($config, 'level', Logger::DEBUG);
        $bubble = true;
        $filePermission = data_get($config, 'permission');
        $processUser = posix_getpwuid(posix_geteuid());
        $processName = $processUser['name'];
        $monolog = new Logger('custom');
        $filename = storage_path('logs/laravel-' . php_sapi_name() . '-' . $processName . '.log');
        $handler = new RotatingFileHandler(
            $filename,
            (int)data_get($config, 'days', 0),
            $level,
            $bubble,
            $filePermission
        );
        $monolog->pushHandler($handler);
        return $monolog;
    }
}
