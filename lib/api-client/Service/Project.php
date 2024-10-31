<?php

namespace Riddle\Api\Service;

class Project extends ApiService
{
    public function list(): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/project/list')['items'] ?? [];
    }
}