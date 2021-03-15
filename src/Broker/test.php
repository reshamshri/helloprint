<?php

require_once __DIR__. "/../../vendor/autoload.php";


use Helloprint\Models\Request;

$model = new Request();

//$model->token = getToken();

$model->id=87;

$model->message = 'resham';
$model->save();
