<?php

namespace src\Api\V1;

class ApiConnector
{
    private $token, $key;

    public function __construct($token, $key)
    {
        $this->token = $token;
        $this->key = $key;
    }

    protected function request($path, $params = null, $method = 'GET')
    {
        $url = RIDDLE_API_V2 . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $this->token", 
            "Key: $this->key"
        ]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if(!$params) {
            $params = ["apiKey" => $this->key];
        }

        if ($params) {
            $this->_prepareArrayValues($params);
            $params["apiKey"] = $this->key;

            if ('POST' === $method) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }
            if ('GET' === $method) {
                $url .= '?' . http_build_query($params);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $responseArray = json_decode($response, true);
        $status = $responseArray['status'];

        if ($status !== 200 && $status !== 404) {
            return false;
        }

        return $responseArray['response'];
    }

    private function _prepareArrayValues(&$params)
    {
        foreach ($params as $_key => $_value) {
            if (is_array($_value)) {
                $params[$_key] = implode(',', $_value);
            }
        }
    }

}
