<?php

namespace Helloprint\Kafka;

use Helloprint\Kafka\Exceptions\ProducerException;
/**
 * Class Producer
 * @package Helloprint\Broker
 */
class Producer
{

    public string $topic;

    public string $message;

    private \RdKafka\Conf $conf;

    private \RdKafka\Producer $producer;

    /**
     * Producer constructor.
     * @param \RdKafka\Conf $config
     */
    public function __construct(\RdKafka\Conf $config)
    {
        $this->producer = new \RdKafka\Producer($config);
        $this->producer->setLogLevel(LOG_DEBUG);

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

    public function publish(): void
    {
        $topic = $this->producer->newTopic($this->topic);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $this->message);
        $this->producer->poll(0);
        $result = $this->producer->flush(10000);

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new ProducerException ('Was unable to flush, messages might be lost!');
        }

    }


}


