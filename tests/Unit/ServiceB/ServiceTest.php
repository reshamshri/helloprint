<?php

namespace Helloprint\Tests\Unit\ServiceB;

use Helloprint\Models\Request as RequestModel;
use Helloprint\ServiceB\Service;
use Helloprint\Tests\BaseTest;

class ServiceTest extends BaseTest
{
    /** @test */
    public function can_store_consumed_data()
    {
        $requestModel = new RequestModel();
        $requestModel->message = 'hello';
        $requestModel->token = 'abc';
        $result = $requestModel->save();

        $payload = [
            "message" => 'hello John',
            "uuid" => $result->uuid,
        ];
        $service = new Service();
        $service->store(json_encode($payload));

        $requestModel = new RequestModel();
        $result = $requestModel->where(["uuid"=>$result->uuid]);

        $this->assertEquals('hello John Bye!!', $result[0]["message"] );

    }
}
