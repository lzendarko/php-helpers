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
     * @param  array       $headers
     * @param  string      $body
     * @param  int         $statusCode
     * @param  string|null $curlError
     * @return void
     */
    public function __construct(
        private array       $headers,
        private string      $body,
        private int         $statusCode,
        private string|null $curlError = null
    )
    {
    }

    /**
     * Returns response headers.
     * 
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Returns response body.
     * 
     * @param  $as   Return as string or object, or later some other formats
     * @return mixed
     */
    public function getBody(string $as = 'string'): mixed
    {
        if ($as === 'object')
        {
            try
            {
                return json_decode($this->body);
            }
            catch (JsonException $exception)
            {
                return null;
            }
        }

        return $this->body;
    }

    /**
     * Returns response status code.
     * 
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Returns response status code.
     * 
     * @return string|null
     */
    public function getCurlError(): ?string
    {
        return $this->curlError;
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
