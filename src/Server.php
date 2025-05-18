<?php

declare(strict_types=1);

namespace PHPHTTP;

final class Server
{
    public readonly int $port;
    public bool $async = false;

    /** @var Router[] */
    private array $routers = [];

    public function __construct(
        public ?HTTPCycleInterface $http_cycle_interface = null,
    ) {
        if (!$this->http_cycle_interface) {
            $this->http_cycle_interface = HTTPCycleFactory::getInstance();
        }
    }

    public function use(Router $router): void
    {
        $router->http_cycle_interface = $this->http_cycle_interface;

        $this->routers[] = $router;
    }

    public function async(bool $async): void
    {
        $this->async = $async;
    }

    public function listen(int $port = 80, ?callable $callback = null): void
    {
        $this->port = $port;

        if ($this->http_cycle_interface) {
            $this->http_cycle_interface->listen($this, $callback);
        }
    }

    public function handleRouters(Request $request, Response $response, ?callable $callback = null): void
    {
        foreach ($this->routers as $router) {
            $found = $router->handle($request, $response);

            if ($callback) {
                $callback($request, $response, $found);
            }
        }
    }
}
