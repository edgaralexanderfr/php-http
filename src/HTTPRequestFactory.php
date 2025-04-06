<?php

declare(strict_types=1);

namespace PHPHTTP;

use PHPHTTP\HTTPRequest\StdHTTPRequest;

final class HTTPRequestFactory
{
    private static ?HTTPRequestInterface $instance = null;

    public static function getInstance(): ?HTTPRequestInterface
    {
        if (self::$instance) {
            return self::$instance;
        }

        return (self::$instance = self::create());
    }

    public static function create(): ?HTTPRequestInterface
    {
        return self::createStdHTTPRequestInstance();
    }

    public static function createStdHTTPRequestInstance(): ?HTTPRequestInterface
    {
        return new StdHTTPRequest();
    }
}
