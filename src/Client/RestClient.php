<?php
namespace Helloprint\Client;

class RestClient
{
    public $timeout = 10;
    public $connectTimeout = 10;

    /**
     * @var \CurlHandle|false|resource
     */
    private $client;


    public function __construct()
    {
        $this->client = curl_init();

        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->client, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($this->client, CURLOPT_TIMEOUT, $this->timeout);
    }

    public function get($url, $params = array(), $headers = array())
    {
        curl_setopt($this->client, CURLOPT_HTTPGET,TRUE);

        if(!empty($params)) $url .= '?' . http_build_query ($params);

        $result = $this->sendRequest($url, $headers);

        return $this->fetchResponse($result);
    }

    public function post($url, $params = array(), $headers = array()): ?array
    {
        curl_setopt($this->client, CURLOPT_POST, TRUE);
        curl_setopt($this->client, CURLOPT_POSTFIELDS, !empty($params) ? json_encode($params) : '');

        $headers = [...$headers, ...['Content-type: application/json'] ];

        $result = $this->sendRequest($url, $headers);

        return $this->fetchResponse($result);
    }

    private function sendRequest($url, $params = array(), $headers = array())
    {
        curl_setopt($this->client, CURLOPT_URL, $url);
        curl_setopt($this->client, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($this->client);

        curl_getinfo($this->client, CURLINFO_HTTP_CODE);

        return $response;
    }

    private function fetchResponse(string $result): ?array
    {
        if(empty($result)) {
            return null;
        }

        return json_decode($result, TRUE);

    }

    public function __destruct()
    {
        curl_close($this->client);
    }
}
