<?php

declare(strict_types=1);

namespace PHPHTTP;

final class Response extends Packet
{
    private int $code = 200;

    public function __construct(
        public ?HTTPCycleInterface $http_cycle_interface = null,
    ) {
        if (!$this->http_cycle_interface) {
            $this->http_cycle_interface = HTTPCycleFactory::getInstance();
        }

        if ($this->http_cycle_interface) {
            $this->setHeaders($this->http_cycle_interface->getResponseHeaders());
        }
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function send(mixed $body, int $code = 200, array $headers = []): true
    {
        $this->setCode($code);
        $this->addHeaders($headers);

        if (is_string($body)) {
            $this->setBody((string) $body);
        } else {
            $this->setBody(json_encode($body));
        }

        if ($this->http_cycle_interface) {
            $this->http_cycle_interface->sendResponse($this);
        }

        return true;
    }
}
