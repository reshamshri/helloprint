<?php

use Helloprint\ServiceA\Service;

require_once __DIR__. "/../../vendor/autoload.php";


while(1) {
    try {
        $serviceA = new Service();
        $consumer = consumer(Service::DEFAULT_CONSUMER_GROUP);
        $message = $serviceA->consume($consumer);
        if($message) {
            $payload  = $serviceA->attachPayload($message);
            $serviceA->produce(producer(), $payload);

        }
        $consumer->close();
    }catch (\Exception $e) {
        echo "Got some exception while processing:" .$e->getMessage();
    }
    sleep(1);
}
