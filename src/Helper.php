<?php

/*
 * The method will return config value based on the provided key.
 * If key not found, it will return default value .
 * If default value not present it will return null, when key not found.
 *
 * @return string|array|null
 * */

use Helloprint\Config;
use Helloprint\Kafka\Consumer;
use Helloprint\Kafka\KafkaConfig;
use Helloprint\Kafka\Message;
use Helloprint\Kafka\Producer;

function config(string $key, $default = null)
{
    $config = Config::buildConfig();

    if (strpos($key, Config::SEPARATOR)) {
        list($parent, $child) = explode(Config::SEPARATOR, $key);

        return $config[$parent][$child] ?? $default;
    } else if (array_key_exists($key, $config)) {

        return $config[$key];
    } else {

        return $default;
    }
}

/*
 * Helper method to generate random unique token
 * */
function getToken(): string
{
    return bin2hex(random_bytes(20));
}

function consumer( string $group = 'helloprint'): Consumer
{
    $kafkaConfig = new KafkaConfig();
    $kafkaConfig->setGroupId($group);
    return  new Consumer($kafkaConfig->getKafkaConsumer(), new Message());
}

function producer(): Producer
{
    $kafkaConfig = new KafkaConfig();
    return new Producer($kafkaConfig->getKafkaProducer());
}

