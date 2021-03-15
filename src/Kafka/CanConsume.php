<?php


namespace Helloprint\Kafka;


interface CanConsume
{
    public function consume();

    public function getTopic(): string;
}
