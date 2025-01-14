<?php

namespace Riddle\Api;

class Response
{
    private $endpoint;
    private $statusCode;
    private $content;

    public function __construct(?string $endpoint, int $statusCode, array $content)
    {
        $this->endpoint = $endpoint;
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}