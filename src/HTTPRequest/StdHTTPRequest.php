<?php

declare(strict_types=1);

namespace PHPHTTP\HTTPRequest;

use PHPHTTP\HTTPRequestInterface;
use PHPHTTP\Request;
use PHPHTTP\Response;

class StdHTTPRequest implements HTTPRequestInterface
{
    public function send(Request $request): Response
    {
        $headers = [];

        $curl = curl_init($request->getURL());

        curl_setopt($curl, CURLOPT_URL, $request->getURL());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request->getHeaders());

        curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$headers) {
            $headers[] = $header;

            return strlen($header);
        });

        $data = $request->getBody();

        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $curl_response = curl_exec($curl);
        $curl_http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $response = new Response();

        $response->setCode((int) $curl_http_code);
        $response->setHeaders($headers);
        $response->setBody((string) $curl_response);

        return $response;
    }
}
