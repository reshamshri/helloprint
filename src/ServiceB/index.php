<?php

use Helloprint\ServiceB\Service;

require_once __DIR__. "/../../vendor/autoload.php";

while(1) {
    try {
        $serviceB = new Service();
        $consumer = consumer(Service::DEFAULT_CONSUMER_GROUP);
        $message = $serviceB->consume($consumer);
        if($message) {

            $serviceB->store($message);

        }
        $consumer->close();
    }catch (\Exception $e) {
        echo "Got some exception while processing:" .$e->getMessage();
    }

    sleep(1);
}
