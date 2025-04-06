<?php

declare(strict_types=1);

namespace PHPHTTP;

function get_current_request(): Request
{
    static $request;

    if ($request) {
        return $request;
    }

    $url = '';

    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
        $url .= 'https://';
    } else {
        $url .= 'http://';
    }

    $url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $request = new Request($_SERVER['REQUEST_METHOD'], $url);
    $request->setHeaders(getallheaders(), false);

    $body = file_get_contents('php://input');

    if ($body === false) {
        $request->setBody('');
    } else {
        $request->setBody((string) $body);
    }

    return $request;
}
