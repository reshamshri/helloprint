<?php

namespace Helloprint\ServiceA;

use Helloprint\Kafka\CanConsume;
use Helloprint\Kafka\Consumable;
use Helloprint\Kafka\Consumer;
use Helloprint\Kafka\CanProduce;
use Helloprint\Kafka\Producible;
use Helloprint\Logger\Log;

class Service implements CanConsume, CanProduce
{
    use Consumable, Producible;

    const DEFAULT_LOG_CHANNEL = 'ServiceA';
    const DEFAULT_CONSUMER_GROUP = 'service-a';
    const CONSUME_ON_TOPIC = 'TopicA';
    const PRODUCE_ON_TOPIC = 'TopicB';

    const DEFAULT_PERSON = ['Joao', 'Bram', 'Gabriel', 'Fehim', 'Eni', 'Patrick', 'Micha', 'Mirzet', 'Liliana',
        'Sebastien'];

    private Log $logger;
    /**
     * @var Consumer
     */
    private Consumer $consumer;


    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->logger = new Log(self::DEFAULT_LOG_CHANNEL);
    }

    /**
     * @return string
     */
    public function getTopicToProduce(): string
    {
        return self::PRODUCE_ON_TOPIC;
    }

    /**
     * @return string
     */
    public function getTopicToConsume(): string
    {
        return self::CONSUME_ON_TOPIC;
    }

    public function close()
    {
        $this->consumer->close();
    }

    /**
     * @param string $message
     * @return string
     */
    public function attachPayload(string $message): string
    {
        $payload = json_decode($message, true);
        $key = array_rand(self::DEFAULT_PERSON, 1);
        $payload["message"]  = sprintf('%s %s', $payload["message"], self::DEFAULT_PERSON[$key]);

        return json_encode($payload);
    }
}
