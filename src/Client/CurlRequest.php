<?php
namespace Helloprint\Client;
/**
 * Class CurlRequest
 * @package Helloprint\Client
 */
class CurlRequest implements HttpRequest
{


    /**
     * @var \CurlHandle|false|resource
     */
    private $client;

    /**
     * CurlRequest constructor.
     */
    public function __construct()
    {
        $this->client = curl_init();
    }

    /**
     * @return bool|string
     */
    public function execute()
    {
        return curl_exec($this->client);
    }

    /**
     * @return \CurlHandle|false|resource
     */
    public function getClient()
    {
        return $this->client;
    }
}
