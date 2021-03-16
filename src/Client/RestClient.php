<?php
namespace Helloprint\Client;
/**
 * Class RestClient
 * @package Helloprint\Client
 */
class RestClient
{
    /**
     * @var int
     */
    public int $timeout = 10;
    /**
     * @var int
     */
    public int $connectTimeout = 10;
    /**
     * @var \CurlHandle|false|resource
     */
    private $client;

    /**
     * RestClient constructor.
     * @param HttpRequest $httpRequest
     */
    public function __construct(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
        $this->client = $httpRequest->getClient();
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->client, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($this->client, CURLOPT_TIMEOUT, $this->timeout);
    }
    /**
     * @param $url
     * @param array $params
     * @param array $headers
     * @return array|null
     */
    public function get($url, $params = array(), $headers = array())
    {
        curl_setopt($this->client, CURLOPT_HTTPGET,TRUE);
        if(!empty($params)) $url .= '?' . http_build_query ($params);
        $result = $this->sendRequest($url, $headers);
        return $this->fetchResponse($result);
    }
    /**
     * @param $url
     * @param array $params
     * @param array $headers
     * @return array|null
     */
    public function post($url, $params = array(), $headers = array()): ?array
    {
        curl_setopt($this->client, CURLOPT_POST, TRUE);
        curl_setopt($this->client, CURLOPT_POSTFIELDS, !empty($params) ? json_encode($params) : '');
        $headers = [...$headers, ...['Content-type: application/json'] ];
        $result = $this->sendRequest($url, $headers);
        return $this->fetchResponse($result);
    }
    /**
     *
     */
    public function __destruct()
    {
        curl_close($this->client);
    }

    /**
     * @param $url
     * @param array $params
     * @param array $headers
     * @return bool|string
     */
    private function sendRequest($url, $params = array(), $headers = array())
    {
        curl_setopt($this->client, CURLOPT_URL, $url);
        curl_setopt($this->client, CURLOPT_HTTPHEADER, $headers);
        $response = $this->httpRequest->execute($this->client);
        curl_getinfo($this->client, CURLINFO_HTTP_CODE);
        return $response;
    }
    /**
     * @param string $result
     * @return array|null
     */
    private function fetchResponse(string $result): ?array
    {
        if(empty($result)) {
            return null;
        }
        return json_decode($result, TRUE);
    }
}















