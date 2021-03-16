<?php

namespace Helloprint\Kafka;


use Helloprint\Kafka\Exceptions\ConsumerException;
use Helloprint\Kafka\Exceptions\ConsumerTimeOutException;
use Helloprint\Logger\Log;

/**
 * Class Consumer
 * @package Helloprint\Broker\Kafka
 */
Class Consumer
{

    /**
     * default consumer timeout in milliseconds
     */
    const DEFAULT_TIMEOUT = 12*1000;

    /**
     * @var \RdKafka\KafkaConsumer
     */
    private \RdKafka\KafkaConsumer $consumer;

    /**
     * @var string
     */
    private string $topic;

    /**
     * @var Log
     */
    private Log $logger;
    /**
     * @var Message
     */
    private Message $kafkaMessage;


    /**
     * Consumer constructor.
     * @param \RdKafka\KafkaConsumer $consumer
     * @param Message $kafkaMessage
     */
    public function __construct(\RdKafka\KafkaConsumer $consumer, Message $kafkaMessage)
    {
        $this->consumer = $consumer;
        $this->kafkaMessage = $kafkaMessage;

        $this->logger = new Log('Consumer');
    }

    /**
     * @param $topic
     */
    public function setTopic($topic): void
    {
        $this->topic = $topic;
    }

    /**
     * @return mixed
     * @throws ConsumerException
     * @throws ConsumerTimeOutException
     */
    public function consumeMessage()
    {
        if (empty($this->topic)) {
            throw new ConsumerException('Consumer topic not set');
        }

        $this->consumer->subscribe([$this->topic]);

        return $this->kafkaMessage->setMessage($this->message())->getPayload();
    }

    public function close()
    {
        $this->consumer->close();
    }

    /**
     * @return mixed
     * @throws ConsumerException
     * @throws ConsumerTimeOutException
     */
    private function message(): \RdKafka\Message
    {
        while (true) {
            $message = $this->consumer->consume(self::DEFAULT_TIMEOUT);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    return $message;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $this->consumer->close();
                    throw new ConsumerTimeOutException(
                        sprintf('Consumer timeout after waiting: %s milliseconds.', self::DEFAULT_TIMEOUT));
                default:
                    throw new ConsumerException('Got exception while consuming:'. $message->errstr());
            }

        }
    }


}
