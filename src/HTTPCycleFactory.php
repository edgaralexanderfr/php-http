<?php

declare(strict_types=1);

namespace PHPHTTP;

use PHPHTTP\HTTPCycle\StdHTTPCycle;

final class HTTPCycleFactory
{
    private static ?HTTPCycleInterface $instance = null;

    public static function getInstance(): ?HTTPCycleInterface
    {
        if (self::$instance) {
            return self::$instance;
        }

        return (self::$instance = self::create());
    }

    public static function create(): ?HTTPCycleInterface
    {
        return self::createStdHTTPCycleInstance();
    }

    public static function createStdHTTPCycleInstance(): ?HTTPCycleInterface
    {
        return new StdHTTPCycle();
    }
}
