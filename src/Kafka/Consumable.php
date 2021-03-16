<?php

namespace Helloprint\Kafka;


use Helloprint\Kafka\Exceptions\ConsumerException;
use Helloprint\Kafka\Exceptions\ConsumerTimeOutException;
use Helloprint\Kafka\Exceptions\KafkaTopicException;

/**
 * Trait Consumable
 * @package Helloprint\Kafka
 */
trait Consumable
{
    /**
     * @param Consumer $consumer
     * @return mixed
     */
    public function consume(Consumer $consumer)
    {
        $consumer->setTopic($this->getTopicToConsume());

        try {

            return $consumer->consumeMessage();
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
