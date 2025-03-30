<?php

declare(strict_types=1);

namespace PHPHTTP;

function get_current_request(): Request
{
    static $request;

    if ($request) {
        return $request;
    }

    $request = new Request($_SERVER['REQUEST_METHOD']);
    $request->setHeaders(getallheaders(), false);

    $body = file_get_contents('php://input');

    if ($body === false) {
        $request->setBody('');
    } else {
        $request->setBody((string) $body);
    }

    return $request;
}
