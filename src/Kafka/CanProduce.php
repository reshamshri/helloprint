<?php


namespace Helloprint\Kafka;


interface CanProduce
{
    public function produce( string $payload);

    public function getTopic(): string;
}
