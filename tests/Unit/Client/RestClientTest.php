<?php
namespace Helloprint\Tests\Unit\Client;
use Helloprint\Client\HttpRequest;
use Helloprint\Client\RestClient;
use Helloprint\Tests\BaseTest;
/**
 * Class RestClientTest
 * @package Helloprint\Tests\Unit\Client
 */
class RestClientTest extends BaseTest
{
    private $httpRequest;
    protected function setUp(): void
    {
        parent::setUp();
        $this->httpRequest = $this->createMock(HttpRequest::class);
        $this->httpRequest->method('execute')->willReturn('{"result":"true"}');
        $this->httpRequest->method('getClient')->willReturn(curl_init());
    }
    /** @test */
    public function can_get_rest_client()
    {
        $client = new RestClient($this->httpRequest);
        $response = $client->get('http://test.com');
        $this->assertJson('{"result":"true"}', json_encode($response));
    }
    /** @test */
    public function can_post_rest_client()
    {
        $client = new RestClient($this->httpRequest);
        $response = $client->post('http://test.com');
        $this->assertJson('{"result":"true"}', json_encode($response));
    }
}















