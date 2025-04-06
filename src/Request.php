<?php

declare(strict_types=1);

namespace PHPHTTP;

final class Request extends Packet
{
    private string $request_method = 'GET';
    private string $request_type = 'GET';
    private string $url = '';

    public function __construct(
        string $method = 'GET',
        string $url = '',
        public ?HTTPRequestInterface $http_request_interface = null,
    ) {
        $this->setMethod($method);
        $this->setURL($url);

        if (!$this->http_request_interface) {
            $this->http_request_interface = HTTPRequestFactory::getInstance();
        }
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

    public function getURL(): string
    {
        return $this->url;
    }

    public function setURL(string $url): void
    {
        $this->url = $url;
    }

    public function send(): ?Response
    {
        if ($this->http_request_interface) {
            return $this->http_request_interface->send($this);
        }

        return null;
    }
}
