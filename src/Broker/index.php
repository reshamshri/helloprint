<?php

require_once __DIR__. "/../../vendor/autoload.php";
set_exception_handler(array(new \Helloprint\Http\Response(), 'exceptionHandler'));

use Helloprint\Http\Route;

/**
 * Handle Request for Routes
 *
 */
function handleRequest()
{
    Route::get('/token',  'Helloprint\Broker\Controller@getMessageByToken' );
    Route::post('/message',  'Helloprint\Broker\Controller@processMessage' );
    Route::notfound();
}

handleRequest();








