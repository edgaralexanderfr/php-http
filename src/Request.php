<?php

declare(strict_types=1);

namespace PHPHTTP;

final class Request extends Packet
{
    private string $request_method = 'GET';
    private string $request_type = 'GET';

    public function __construct(
        string $method = 'GET',
    ) {
        $this->setMethod($method);
    }

    public string $method {
        get => $this->request_method;
    }

    public string $type {
        get => $this->request_type;
    }

    public function getMethod(): string
    {
        return $this->request_method;
    }

    public function getType(): string
    {
        return $this->request_type;
    }

    private function setMethod(string $method): void
    {
        $method_to_set = strtoupper($method);

        if (!RequestType::isValidType($method)) {
            $method_to_set = 'GET';
        }

        $this->request_method = $method_to_set;
        $this->request_type = $method_to_set;
    }
}
