<?php

namespace src\Api\V1;

class Api extends ApiConnector
{
    public function getRiddleTypes()
    {
        return $this->request('/riddle/get/types');
    }

    public function getRiddleList($params = null)
    {
        return $this->request('/riddle/get/list', $params);
    }

    public function getRiddleEmbedCode($params)
    {
        return $this->request('/riddle/get/embed-code', $params, 'GET');
    }

    public function getTeams()
    {
        return $this->request('/team/get/list');
    }

    public function getTags($params = null)
    {
        return $this->request('/tag/get/list', $params);
    }

    public function getAuthors($teamId = null)
    {
        return $this->request('/author/get/list', ['teamId' => $teamId]);
    }

    public function getLeadFields($riddleId)
    {
        return $this->request('/riddle/get/lead-fields?riddleId=' . $riddleId);
    }
}