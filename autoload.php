<?php

declare(strict_types=1);

if (!defined('PHPHTTP')) {
    define('PHPHTTP', 1);

    function php_http_autoload(string $class): void
    {
        $project_root_namespace = 'PHPHTTP\\';

        if (!str_contains($class, $project_root_namespace)) {
            return;
        }

        $class_path = str_replace([$project_root_namespace, '\\'], ['', '/'], $class);
        $file_path = __DIR__ . "/src/{$class_path}.php";

        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }

    spl_autoload_register('php_http_autoload');
}
