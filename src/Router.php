<?php

declare(strict_types=1);

namespace PHPHTTP;

final class Router
{
    private array $routes = [];
    private bool $ran = false;

    public function __construct(
        public ?HTTPCycleInterface $http_cycle_interface = null,
    ) {
        if (!$this->http_cycle_interface) {
            $this->http_cycle_interface = HTTPCycleFactory::getInstance();
        }

        if (http_response_code() !== false) {
            register_shutdown_function(function () {
                $this->run();
            });
        }
    }

    public function run(): void
    {
        if (!$this->ran) {
            $this->ran = true;

            if ($this->http_cycle_interface) {
                $this->http_cycle_interface->run($this);
            }
        }
    }

    public function handle(Request $request, Response $response, bool $ignore_paths = false): bool
    {
        $type = strtolower($request->type);

        if (isset($this->routes[$type])) {
            return $this->runCallbacks($type, $request, $response, $ignore_paths);
        } else if (isset($this->routes['all'])) {
            return $this->runCallbacks('all', $request, $response, $ignore_paths);
        }

        return false;
    }

    public function all(string $path, callable ...$callback): void
    {
        $this->route('all', $path, ...$callback);
    }

    public function get(string $path, callable ...$callback): void
    {
        $this->route('get', $path, ...$callback);
    }

    public function head(string $path, callable ...$callback): void
    {
        $this->route('head', $path, ...$callback);
    }

    public function options(string $path, callable ...$callback): void
    {
        $this->route('options', $path, ...$callback);
    }

    public function trace(string $path, callable ...$callback): void
    {
        $this->route('trace', $path, ...$callback);
    }

    public function put(string $path, callable ...$callback): void
    {
        $this->route('put', $path, ...$callback);
    }

    public function delete(string $path, callable ...$callback): void
    {
        $this->route('delete', $path, ...$callback);
    }

    public function post(string $path, callable ...$callback): void
    {
        $this->route('post', $path, ...$callback);
    }

    public function patch(string $path, callable ...$callback): void
    {
        $this->route('patch', $path, ...$callback);
    }

    public function connect(string $path, callable ...$callback): void
    {
        $this->route('connect', $path, ...$callback);
    }

    private function route(string $method, string $path, callable ...$callback): void
    {
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        $this->routes[$method][$path] = $callback;
    }

    private function runCallbacks(string $type, Request $request, Response $response, bool $ignore_paths = false): bool
    {
        foreach ($this->routes[$type] as $path => $callbacks) {
            if ($ignore_paths || $path == $request->path) {
                $result = true;
                $i = 0;

                while ($result && isset($callbacks[$i])) {
                    $result = $callbacks[$i]($request, $response);

                    $i++;
                }

                return true;
            }
        }

        return false;
    }
}
