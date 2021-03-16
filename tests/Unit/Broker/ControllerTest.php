<?php


namespace Helloprint\Tests\Unit\Broker;


use Helloprint\Broker\Controller;
use Helloprint\Models\ModelNotFoundException;
use Helloprint\Models\Request as RequestModel;
use Helloprint\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Request;

class ControllerTest extends BaseTest
{
    /** @test */
    public function can_save_message_and_get_token()
    {

        $controller = new Controller();
        $request = Request::create('/message','GET', ['message' => 'Hi']);

        $producer = $this->createMock(\Helloprint\Kafka\Producer::class);

        $controller->setProducer($producer);

        $result = $controller->processMessage($request);

        $requestModel = new RequestModel();
        $data = $requestModel->where(["token" => $result]);

        $this->assertEquals($data[0]["token"], $result);
        $this->assertEquals($data[0]["message"], 'Hi');
    }

    /** @test */
    public function can_get_message_by_token()
    {
        $requestModel = new RequestModel();
        $requestModel->message = 'hello world';
        $requestModel->token = '123';
        $requestModel->insert();

        $controller = new Controller();
        $request = Request::create('/token','GET', ['token' => '123']);
        $result = $controller->getMessageByToken($request);

        $this->assertEquals('hello world', $result);
    }

    /** @test */
    public function throw_model_not_found_exception_when_token_not_found()
    {
        $requestModel = new RequestModel();
        $requestModel->message = 'hello world';
        $requestModel->token = '123';
        $requestModel->insert();

        $controller = new Controller();
        $request = Request::create('/token','GET', ['token' => '456']);
        $this->expectException(ModelNotFoundException::class);
        $controller->getMessageByToken($request);
    }
}
