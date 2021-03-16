<?php


namespace Helloprint\Kafka;


/**
 * Trait Producible
 * @package Helloprint\Kafka
 */
trait Producible
{
    /**
     * @param Producer $producer
     * @param string $payload
     * @throws Exceptions\ProducerException
     */
    public function produce(Producer $producer, string $payload)
    {
        $producer->setTopic($this->getTopicToProduce());
        $producer->setMessage($payload);
        $producer->publish();
        $this->logger->info("Broker published Message successfully:".$payload );
    }
}
