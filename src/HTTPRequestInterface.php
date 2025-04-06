<?php

declare(strict_types=1);

namespace PHPHTTP;

interface HTTPRequestInterface
{
    public function send(Request $request): Response;
}
