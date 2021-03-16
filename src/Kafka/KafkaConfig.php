<?php

namespace Helloprint\Kafka;

use Helloprint\Kafka\Exceptions\ConsumerException;

/**
 * Class KafkaConfig
 * @package Helloprint\Kafka
 */
Class KafkaConfig
{
    /**
     * @var \RdKafka\Conf
     */
    private \RdKafka\Conf $conf;

    const DEFAULT_OFFSET_RESET = 'earliest';

    const DEFAULT_GROUP_ID = 'group';


    /**
     * KafkaConfig constructor.
     */
    public function __construct()
    {
        $this->conf = new \RdKafka\Conf();
        $this->conf->set('metadata.broker.list', config('kafka.host'));
    }

    /**
     * @return \RdKafka\KafkaConsumer
     */
    public function getKafkaConsumer(): \RdKafka\KafkaConsumer
    {
        $this->conf->set('group.id', $groupId ?? self::DEFAULT_GROUP_ID);
        $this->conf->set('auto.offset.reset', self::DEFAULT_OFFSET_RESET);
        $this->conf->setRebalanceCb(array($this, 'rebalanceCb'));

        return new \RdKafka\KafkaConsumer($this->conf);
    }

    /**
     * @return \RdKafka\Producer
     */
    public function getKafkaProducer(): \RdKafka\Producer
    {
        $producer = new \RdKafka\Producer($this->conf);
        $producer->setLogLevel(LOG_DEBUG);

        return $producer;
    }

    /**
     * @param string $groupId
     */
    public function setGroupId(string $groupId)
    {
        $this->conf->set('group.id', $groupId);
    }

    /**
     * @param \RdKafka\KafkaConsumer $kafka
     * @param $err
     * @param array|null $partitions
     * @throws ConsumerException
     */
    public function rebalanceCb (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
        switch ($err) {
            case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                $kafka->assign($partitions);
                break;

            case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                $kafka->assign(NULL);
                break;

            default:
                throw new ConsumerException($err);
        }
    }
}
