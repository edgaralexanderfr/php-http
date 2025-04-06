<?php

declare(strict_types=1);

namespace PHPHTTP;

abstract class Packet
{
    protected array $headers = [];
    protected string $body = '';

    public function getHeaders(bool $separated_by_colon = true): array
    {
        if ($separated_by_colon) {
            $headers = [];

            foreach ($this->headers as $name => $value) {
                $headers[] = "{$name}: $value";
            }

            return $headers;
        }

        return $this->headers;
    }

    public function getHeader(string $name): ?string
    {
        $lowercase_name = strtolower($name);

        foreach ($this->headers as $name => $value) {
            if ($lowercase_name == strtolower($name)) {
                return $value;
            }
        }

        return null;
    }

    public function setHeaders(array $headers, bool $separated_by_colon = true): void
    {
        if ($separated_by_colon) {
            $headers_to_set = [];

            foreach ($headers as $header) {
                $name_value = explode(':', $header);
                $name = trim($name_value[0] ?? '');
                $value = trim($name_value[1] ?? '');

                if ($name) {
                    $headers_to_set[$name] = $value;
                }
            }

            $this->headers = $headers_to_set;
        } else {
            $this->headers = $headers;
        }
    }

    public function addHeaders(array $headers): void
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function text(): string
    {
        return $this->body;
    }

    /**
     * @return \PHPTypes\Data\json|stdClass
     */
    public function json(?bool $associative = null, int $depth = 512, int $flags = 0): mixed
    {
        if (function_exists('\PHPTypes\Data\json')) {
            return \PHPTypes\Data\json($this->body);
        }

        return json_decode($this->body, $associative, $depth, $flags);
    }

    public function reset(): void
    {
        $this->headers = [];
        $this->body = '';
    }
}
