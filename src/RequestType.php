<?php

declare(strict_types=1);

namespace PHPHTTP;

final class RequestType
{
    public const string GET = 'GET';
    public const string HEAD = 'HEAD';
    public const string OPTIONS = 'OPTIONS';
    public const string TRACE = 'TRACE';
    public const string PUT = 'PUT';
    public const string DELETE = 'DELETE';
    public const string POST = 'POST';
    public const string PATCH = 'PATCH';
    public const string CONNECT = 'CONNECT';

    public const array TYPES = [
        self::GET => self::GET,
        self::HEAD => self::HEAD,
        self::OPTIONS => self::OPTIONS,
        self::TRACE => self::TRACE,
        self::PUT => self::PUT,
        self::DELETE => self::DELETE,
        self::POST => self::POST,
        self::PATCH => self::PATCH,
        self::CONNECT => self::CONNECT,
    ];

    public static function isValidType(string $type): bool
    {
        return isset(self::TYPES[$type]);
    }
}
