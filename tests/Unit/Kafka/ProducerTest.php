<?php


namespace Helloprint\Tests\Unit\Kafka;


use Helloprint\Kafka\Exceptions\ProducerException;
use Helloprint\Kafka\Producer;
use Helloprint\Tests\BaseTest;

class ProducerTest extends BaseTest
{
    /** @test */
    public function producer_can_publish()
    {

        $producerTopic = \Mockery::mock(RdKafka\ProducerTopic::class)->makePartial();
        $producerTopic->shouldReceive('produce')->andReturn(true);


        $kafkaProducer = \Mockery::mock(\RdKafka\Producer::class)->makePartial();
        $kafkaProducer->shouldReceive('newTopic')->andReturn($producerTopic);
        $kafkaProducer->shouldReceive('poll')->andReturn(null);
        $kafkaProducer->shouldReceive('flush')->andReturn(0);


        $producer = new Producer($kafkaProducer);
        $producer->setTopic('helloprint');
        $producer->setMessage('Hello');

        $this->assertTrue($producer->publish());


    }

    /** @test */
    public function producer_will_throw_exception_when_unable_to_flush()
    {

        $producerTopic = \Mockery::mock(RdKafka\ProducerTopic::class)->makePartial();
        $producerTopic->shouldReceive('produce')->andReturn(true);


        $kafkaProducer = \Mockery::mock(\RdKafka\Producer::class)->makePartial();
        $kafkaProducer->shouldReceive('newTopic')->andReturn($producerTopic);
        $kafkaProducer->shouldReceive('poll')->andReturn(null);
        $kafkaProducer->shouldReceive('flush')->andReturn(1);


        $producer = new Producer($kafkaProducer);
        $producer->setTopic('helloprint');
        $producer->setMessage('Hello');
        $this->expectException(ProducerException::class);
        $producer->publish();
    }

}
