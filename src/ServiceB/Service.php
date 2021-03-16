<?php

namespace Helloprint\ServiceB;

use Helloprint\Exceptions\ModelException;
use Helloprint\Kafka\CanConsume;
use Helloprint\Kafka\Consumable;
use Helloprint\Logger\Log;
use Helloprint\Models\Request;

class Service implements CanConsume
{
    use Consumable;
    const DEFAULT_LOG_CHANNEL = 'ServiceB';
    const DEFAULT_MESSAGE = 'Bye!!';
    const DEFAULT_CONSUMER_GROUP = 'service-b';
    const CONSUME_ON_TOPIC = 'TopicB';

    private Log $logger;


    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->logger = new Log(self::DEFAULT_LOG_CHANNEL);
    }

    /**
     * @param string $message
     * @throws ModelException
     */
    public function store(string $message)
    {
        $payload = json_decode($message, true);
        $payload["message"]  = sprintf('%s %s', $payload["message"], self::DEFAULT_MESSAGE);
        $this->updateRequester($payload);
    }

    /**
     * @return string
     */
    public function getTopicToConsume(): string
    {
        return self::CONSUME_ON_TOPIC;
    }

    /**
     * @param array $payload
     * @throws ModelException
     */
    private function updateRequester(array $payload)
    {
        if(!array_key_exists('uuid', $payload)) {
            throw new \InvalidArgumentException('UUID missing in the payload');
        }

        $this->logger->info("Service B, updating db:".json_encode($payload));
        $requester = new Request();
        $result = $requester->where(['uuid' => $payload["uuid"]]);

        $requester->id = $result[0]["id"];
        $requester->message = $payload["message"];
        $requester->update();

        echo "Updated the message!!";
    }


}
