<?php

namespace Helloprint\Kafka;

use Helloprint\Kafka\Exceptions\ProducerException;
/**
 * Class Producer
 * @package Helloprint\Broker
 */
class Producer
{

    /**
     * @var string
     */
    public string $topic;

    /**
     * @var string
     */
    public string $message;

    /**
     * @var \RdKafka\Producer
     */
    private \RdKafka\Producer $producer;

    /**
     * Producer constructor.
     * @param \RdKafka\Producer $producer
     */
    public function __construct(\RdKafka\Producer $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @param string $topic
     */
    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Publish message on Kafka
     * @return bool
     * @throws ProducerException
     */
    public function publish(): bool
    {
        $topic = $this->producer->newTopic($this->topic);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $this->message);
        $this->producer->poll(0);
        $result = $this->producer->flush(10000);

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new ProducerException ('Was unable to flush, messages might be lost!');
        }

        return true;
    }

}


