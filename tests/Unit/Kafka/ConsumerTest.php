<?php


namespace Helloprint\Tests\Unit\Kafka;


use Helloprint\Kafka\Consumer;
use Helloprint\Kafka\Exceptions\ConsumerTimeOutException;
use Helloprint\Kafka\Message;
use Helloprint\Tests\BaseTest;

class ConsumerTest extends BaseTest
{
    /** @test */
    public function can_consume_message()
        {
            $kafkaConsumer = \Mockery::mock(\RdKafka\KafkaConsumer::class)->makePartial();
            $kafkaConsumer->shouldReceive('subscribe')->andReturn(null);
            $kafkaMessage = new \RdKafka\Message();
            $kafkaMessage->err = RD_KAFKA_RESP_ERR_NO_ERROR;
            $kafkaMessage->payload = 'hello world';
            $kafkaMessage->topic_name = 'abc';


            $kafkaConsumer->shouldReceive('consume')
                    ->withAnyArgs()
                    ->andReturn($kafkaMessage);

            $consumer = new Consumer($kafkaConsumer, new Message());
            $consumer->setTopic('abc');
            $result = $consumer->consumeMessage();
            $this->assertEquals('hello world', $result);

        }


    /** @test */
    public function consumer_will_throw_exception_on_timeout()
    {
        $kafkaConsumer = \Mockery::mock(\RdKafka\KafkaConsumer::class)->makePartial();
        $kafkaConsumer->shouldReceive('subscribe')->andReturn(null);
        $kafkaMessage = new \RdKafka\Message();
        $kafkaMessage->err = RD_KAFKA_RESP_ERR__TIMED_OUT;

        $kafkaConsumer->shouldReceive('consume')
            ->withAnyArgs()
            ->andReturn($kafkaMessage);

        $kafkaConsumer->shouldReceive('close')->andReturn(null);

        $consumer = new Consumer($kafkaConsumer, new Message());
        $consumer->setTopic('abc');
        $this->expectException(ConsumerTimeOutException::class);
        $consumer->consumeMessage();

    }
}
