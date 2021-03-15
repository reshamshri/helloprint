<?php

require_once __DIR__. "/../../vendor/autoload.php";
//set_exception_handler(array(new \Helloprint\Broker\Response(), 'exceptionHandler'));

use Helloprint\Http\Route;


function handleRequest() {
    Route::get('/token',  'Helloprint\Broker\Controller@getMessageByToken' );
    Route::get('/message',  'Helloprint\Broker\Controller@processMessage' );
    Route::notfound();
}

handleRequest();








