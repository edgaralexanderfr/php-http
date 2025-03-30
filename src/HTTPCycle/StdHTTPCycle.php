<?php

declare(strict_types=1);

namespace PHPHTTP\HTTPCycle;

use PHPHTTP\HTTPCycleInterface;
use PHPHTTP\Response;

class StdHTTPCycle implements HTTPCycleInterface
{
    public function getResponseHeaders(): array
    {
        $headers = headers_list();
        $headers[] = 'Content-Type: application/json';

        return $headers;
    }

    public function sendResponse(Response $response): void
    {
        $headers = $response->getHeaders();

        foreach ($headers as $header) {
            header($header);
        }

        http_response_code($response->getCode());

        echo $response->getBody();

        die();
    }
}
