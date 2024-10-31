<?php

namespace Riddle\Api\Service;

class Team extends ApiService
{
    public function list(): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/team/list')['items'] ?? [];
    }
}