<?php

use Response;

/**
 * Helper class used for working with PHP cURL
 */
class Helper
{
    /**
     * Executes GET request.
     * 
     * @param  string   $url
     * @param  array    $headers
     * @return Response
     */
    public function get(string $url, array $headers = []): Response
    {
        return $this->request($url, 'GET', $headers);
    }

    /**
     * Executes POST request.
     * 
     * @param  string   $url
     * @param  array    $headers
     * @param  mixed    $body
     * @return Response
     */
    public function post(string $url, array $headers = [], mixed $body): Response
    {
        return $this->request($url, 'POST', $headers, $body);
    }

    /**
     * Executes PUT request.
     * 
     * @param  string   $url
     * @param  array    $headers
     * @param  mixed    $body
     * @return Response
     */
    public function put(string $url, array $headers = [], mixed $body): Response
    {
        return $this->request($url, 'PUT', $headers, $body);
    }

    /**
     * Executes PATCH request.
     * 
     * @param  string   $url
     * @param  array    $headers
     * @param  mixed    $body
     * @return Response
     */
    public function patch(string $url, array $headers = [],  mixed $body): Response
    {
        return $this->request($url, 'PATCH', $headers, $body);
    }

    /**
     * Executes DELETE request.
     * 
     * @param  string   $url
     * @param  array    $headers
     * @return Response
     */
    public function delete(string $url, array $headers = []): Response
    {
        return $this->request($url, 'DELETE', $headers);
    }

    /**
     * Internal function that executes the actual request.
     * 
     * @param string $url
     * @param string $method
     * @param array  $headers
     * @param mixed  $body
     */
    private function request(
        string $url,
        string $method  = 'GET',
        array  $headers = [],
        mixed  $body    = null
    ): Response
    {
        $curl = curl_init($url);

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers
        ];
        if ($body)
        {
            $options[CURLOPT_POSTFIELDS] =
                in_array(
                    'content-type: application/json',
                    array_map('strtolower', $headers)
                )
                ? json_encode($body)
                : http_build_query($body);
        }
        curl_setopt_array($curl, $options);

        $responseHeaders = [];
        curl_setopt(
            $curl,
            CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$responseHeaders)
            {
                if (strpos($header, ':') !== false)
                {
                    $responseHeaders[] = trim($header);
                }

                return strlen($header);
            }
        );

        $responseBody = curl_exec($curl);
        $curlError    = curl_error($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);


        return new Response(
            $responseHeaders,
            $responseBody,
            $responseCode,
            $curlError
        );
    }
}
