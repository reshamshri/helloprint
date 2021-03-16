<?php


namespace Helloprint\Tests\Unit\Kafka;


use Helloprint\Kafka\Message;
use Helloprint\Tests\BaseTest;

class MessageTest extends BaseTest
{


    /** @test */
    public function can_set_and_fetch_payload()
    {
        $kafkaMessage = new \RdKafka\Message();
        $kafkaMessage->topic_name="Topic_Test";
        $kafkaMessage->payload="helloprint";

        $message = new Message();
        $result = $message->setMessage($kafkaMessage);
        $this->assertInstanceOf(Message::class,$result);
        $this->assertSame('helloprint',$message->getPayload());

    }


}
