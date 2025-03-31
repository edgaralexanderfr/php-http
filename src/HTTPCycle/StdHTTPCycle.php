<?php

declare(strict_types=1);

namespace PHPHTTP\HTTPCycle;

use PHPHTTP\HTTPCycleInterface;
use PHPHTTP\Response;
use PHPHTTP\Router;

use function PHPHTTP\get_current_request;

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

    public function run(Router $router): void
    {
        $request = get_current_request();
        $response = new Response();

        $router->handle($request, $response, true);
    }
}
