<?php


namespace Helloprint\Kafka;


/**
 * Interface CanConsume
 * @package Helloprint\Kafka
 */
interface CanConsume
{
    /**
     * @param Consumer $consumer
     * @return mixed
     */
    public function consume(Consumer $consumer);

    /**
     * @return string
     */
    public function getTopicToConsume(): string;
}
