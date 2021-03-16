<?php

namespace Helloprint\Tests\Unit\ServiceA;

use Helloprint\Kafka\Consumer;
use Helloprint\ServiceA\Service;
use Helloprint\Tests\BaseTest;

class ServiceTest extends BaseTest
{
    /** @test */
    public function consumable_will_return_current_consume_message()
    {
        $consumer = \Mockery::mock(Consumer::class);
        $consumer->shouldReceive('consumeMessage')->andReturn('new message');
        $consumer->shouldReceive('setTopic')->andReturn(null);

        $service = new Service();
        $result = $service->consume($consumer);
        $this->assertEquals('new message', $result);
    }

    /** @test */
    public function can_attach_payload()
    {
        $service = new Service();

        $result = $service->attachPayload('{"message": "Hello"}');

        $payload = json_decode($result, true);
        list($message, $attachment) = explode(" ", $payload["message"]);

        $this->assertContains($attachment, Service::DEFAULT_PERSON);
    }

}
