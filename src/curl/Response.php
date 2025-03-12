<?php

/**
 * A class representing the standard response
 * returned by cURL Helper's HTTP requests
 */
class Response
{
    /**
     * Constructs the object.
     *
     * @param  array  $headers
     * @param  string $body
     * @param  int    $statusCode
     * @param  string $curlError
     * @return void
     */
    public function __construct(
        private array  $headers,
        private string $body,
        private int    $statusCode,
        private string $curlError
    )
    {
    }

    /**
     * Returns whether the response is a success.
     * 
     * @return bool
     */
    public function isSuccess(): bool
    {
        return str_starts_with((string)$this->statusCode, '2');
    }
}
