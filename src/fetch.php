<?php

declare(strict_types=1);

namespace PHPHTTP;

function get(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'GET', $headers, $body);
}

function head(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'HEAD', $headers, $body);
}

function options(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'OPTIONS', $headers, $body);
}

function trace(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'TRACE', $headers, $body);
}

function put(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'PUT', $headers, $body);
}

function delete(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'DELETE', $headers, $body);
}

function post(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'POST', $headers, $body);
}

function patch(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'PATCH', $headers, $body);
}

function connect(string $url, array $headers = [], string $body = ''): mixed
{
    return request($url, 'CONNECT', $headers, $body);
}

function request(string $url, string $method = 'GET', array $headers = [], string $body = ''): mixed
{
    $response = fetch($url, $method, $headers, $body);

    if ($response && ($content_type = $response->getHeader('Content-Type')) && str_contains(strtolower((string) $content_type), 'application/json')) {
        $data = $response->json();

        if ($data === null) {
            return $response->text();
        }

        return $data;
    }

    return $response->text();
}

function fetch(string $url, string $method = 'GET', array $headers = [], string $body = ''): ?Response
{
    $request = new Request($method, $url);
    $request->setHeaders($headers, false);
    $request->setBody($body);

    return $request->send();
}
