<?php

namespace Helloprint\Kafka;

Class Config
{
    private \RdKafka\Conf $conf;

    public function __construct()
    {
        $this->conf = new \RdKafka\Conf();
        $this->conf->set('metadata.broker.list', config('kafka.host'));
    }

    public function getKafkaConf(): \RdKafka\Conf
    {
        return $this->conf;
    }
}
