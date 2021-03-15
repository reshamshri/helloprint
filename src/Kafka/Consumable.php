<?php

namespace Helloprint\Kafka;


use Helloprint\Kafka\Exceptions\ConsumerException;
use Helloprint\Kafka\Exceptions\ConsumerTimeOutException;
use Helloprint\Kafka\Exceptions\KafkaTopicException;

trait Consumable
{
    public function consume()
    {
        $this->consumer = new Consumer($this->brokerConfig->getKafkaConf(), self::DEFAULT_CONSUMER_GROUP);

        $this->consumer->setTopic(self::CONSUME_ON_TOPIC);

        try {
            return $this->consumer->consumeMessage();
        } catch (ConsumerException | KafkaTopicException $e) {
            $error = sprintf("Got exception while consuming message %s \n", $e->getMessage());
            $this->logger->error($error, [], $e);
            echo $error;
        } catch (ConsumerTimeOutException $e) {
            $error = sprintf("Got consumer timeout error while consuming message %s \n", $e->getMessage());
            $this->logger->error($error, [], $e);
            echo $error;
        }
    }
}
