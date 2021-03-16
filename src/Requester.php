<?php

require_once __DIR__. "/../vendor/autoload.php";

use Helloprint\Client\CurlRequest;
use Helloprint\Client\RestClient;

$client = new RestClient(new CurlRequest());

$message = 'Hi';
$response = $client->post('http://localhost:8000/message', ["message" => $message] );
if(!$response['data']) {
    die('no token found');
}

echo "Fetching message for token:".$response['data']." \n";
$time = 0;
while(1) {
    echo "..";
    $result = $client->get('http://localhost:8000/token?token='.$response['data']);
    if(!empty($result["data"]) && $message != $result["data"]) {
        var_dump($result);
        break;
    }
    usleep(50000);
    $time += 50000;
    if($time > 1000000) {
        echo "\n Timeout!! No response got from the broker..\n";
        break;
    }
}



