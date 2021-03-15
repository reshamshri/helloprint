<?php

namespace Helloprint\Kafka;


use Helloprint\Kafka\Exceptions\ConsumerException;
use Helloprint\Kafka\Exceptions\ConsumerTimeOutException;
use Helloprint\Kafka\Exceptions\KafkaTopicException;
use Helloprint\Logger\Log;

/**
 * Class Consumer
 * @package Helloprint\Broker\Kafka
 */
Class Consumer
{
    const DEFAULT_OFFSET_RESET = 'earliest';
    const DEFAULT_GROUP_ID = 'group';
    const DEFAULT_TIMEOUT = 12*1000;

    private \RdKafka\KafkaConsumer $consumer;

    private string $topic;

    private Log $logger;


    /**
     * Consumer constructor.
     * @param \RdKafka\Conf $config
     * @param string|null $groupId
     */
    public function __construct(\RdKafka\Conf $config, string $groupId = null)
    {
        $config = $this->init( $config , $groupId);
        $this->consumer = new \RdKafka\KafkaConsumer($config);
        $this->logger = new Log('Consumer');
    }

    public function init(\RdKafka\Conf $config, string $groupId): \RdKafka\Conf
    {
        $config->set('group.id', $groupId ?? self::DEFAULT_GROUP_ID);
        $config->set('auto.offset.reset', self::DEFAULT_OFFSET_RESET);
        $config->setRebalanceCb(array($this, 'rebalanceCb'));

        return $config;
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
     * @throws KafkaTopicException
     */
    public function consumeMessage()
    {
        if (empty($this->topic)) {
            throw new KafkaTopicException('Topic not found.');
        }

        $this->consumer->subscribe([$this->topic]);

        return (new Message($this->message()))->getPayload();
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
    public function message(): \RdKafka\Message
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

    public function rebalanceCb (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
        switch ($err) {
            case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                echo "Assign: ";
                $kafka->assign($partitions);
                break;

            case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                echo "Revoke: ";
                $kafka->assign(NULL);
                break;

            default:
                throw new ConsumerException($err);
        }
    }

}
