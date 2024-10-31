<?php

namespace Riddle\Api\Service;

use Riddle\Api\Client;

class ApiService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}