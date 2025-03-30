<?php

declare(strict_types=1);

namespace PHPHTTP;

interface HTTPCycleInterface
{
    public function getResponseHeaders(): array;
    public function sendResponse(Response $response): void;
}
