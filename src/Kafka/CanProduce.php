<?php


namespace Helloprint\Kafka;


/**
 * Interface CanProduce
 * @package Helloprint\Kafka
 */
interface CanProduce
{
    /**
     * @param Producer $producer
     * @param string $payload
     * @return mixed
     */
    public function produce(Producer $producer, string $payload);

    /**
     * @return string
     */
    public function getTopicToProduce(): string;
}
