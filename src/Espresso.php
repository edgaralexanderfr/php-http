<?php

declare(strict_types=1);

namespace PHPHTTP;

use stdClass;
use PHPHTTP\HTTPCycleInterface;

final class Espresso implements HTTPCycleInterface
{
    private const string LINE_BREAK = "\r\n";

    /** @var resource */
    private $server_socket = null;

    public function getResponseHeaders(): array
    {
        return [];
    }

    public function sendResponse(Response $response): void {}

    public function run(Router $router): void {}

    public function listen(Server $server, ?callable $callback): void
    {
        $error_code = 0;
        $error_message = null;

        $this->server_socket = stream_socket_server("tcp://0.0.0.0:{$server->port}", $error_code, $error_message);

        if ($callback) {
            $callback();
        }

        while (true) {
            $client = @stream_socket_accept($this->server_socket);

            if (!$client) {
                continue;
            }

            if ($server->async) {
                $pid = pcntl_fork();

                if (!$pid) {
                    $this->handle($server, $client);

                    exit(0);
                }
            } else {
                $this->handle($server, $client);
            }
        }
    }

    /**
     * @param resource $client
     */
    private function handle(Server $server, &$client): void
    {
        $http = $this->build($server, $client);

        $server->handleRouters($http->request, $http->response, function (Request $request, Response $response, bool $found) use ($client) {
            if ($found) {
                if (!$response->getHeader('Content-Length')) {
                    $response->setHeader('Content-Length', (string) strlen($response->getBody()));
                }

                if (!$response->getHeader('Content-Type')) {
                    $response->setHeader('Content-Type', 'text/html');
                }
            } else {
                $response->setCode(404);
                $response->setHeader('Content-Length', '0');
            }

            if (!$response->getHeader('Server')) {
                $response->setHeader('Server', 'PHP HTTP Espresso');
            }

            if (!$response->getHeader('Connection')) {
                $response->setHeader('Connection', 'Closed');
            }

            $response_packet = "HTTP/1.1 {$response->getCode()}" . self::LINE_BREAK;
            $response_packet .= implode("\r\n", $response->getHeaders(true)) . self::LINE_BREAK;
            $response_packet .= self::LINE_BREAK;
            $response_packet .= $response->getBody();

            fwrite($client, $response_packet);
            fclose($client);
        });
    }

    /**
     * @param resource $client
     */
    private function build(Server $server, &$client): stdClass
    {
        /** @var ?Request */
        $request = null;
        $response = new Response($server->http_cycle_interface);
        $payload = '';
        $l = 1;

        while (($line = trim(fgets($client))) != '') {
            $http_header = explode(' ', $line);

            if ($l == 1) {
                $request = new Request($http_header[0]);
                $request->path = $http_header[1];

                echo $line . PHP_EOL;
            } else {
                $request->setHeader($http_header[0], $http_header[1]);
            }

            $l++;
        }

        $bytes_to_read = socket_get_status($client)['unread_bytes'];

        if ($bytes_to_read > 0) {
            $payload = fread($client, $bytes_to_read);
        }

        $request->setBody($payload);

        return (object) [
            'request' => $request,
            'response' => $response,
        ];
    }
}
