<?php


namespace Helloprint\Kafka;


trait Producible
{
    public function produce(string $payload)
    {
        $producer = new Producer((new Config())->getKafkaConf());
        $producer->setTopic($this->getTopic());
        $producer->setMessage($payload);
        $producer->publish();
        $this->logger->info("Broker published Message successfully:".$payload );
    }
}
