<?php

require_once __DIR__. "/../vendor/autoload.php";

use Helloprint\Client\RestClient;

$client = new RestClient();

$message = 'Hi';
$response = $client->get('http://localhost:8000/message?message='.$message);
var_dump($response);

$data = $client->get('http://localhost:8000/token?token='.$response['data']);
var_dump($data);



