<?php

namespace Helloprint\Kafka;


use Helloprint\Logger\Log;

/**
 * Class Message
 * @package Helloprint\Broker\Kafka
 */
Class Message
{
    /**
     * @var \RdKafka\Message
     */
    private \RdKafka\Message $message;

    /**
     * @var Log
     */
    private Log $logger;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->logger = new Log('Message');
    }

    /**
     * @param \RdKafka\Message $message
     * @return $this
     */
    public function setMessage(\RdKafka\Message $message):self
    {
        $this->message = $message;
        $this->logMessage($message);

        return $this;
    }
    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->message->payload;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->message->topic_name;
    }

    /**
     * @param \RdKafka\Message $message
     */
    private function logMessage(\RdKafka\Message $message): void
    {
        $this->logger->info(
            sprintf('Payload found: %s , against topic: %s',
                $this->getPayload(), $this->getTopic()),
            [
                'raw_message' => json_encode($message)
            ]
        );
    }
}
