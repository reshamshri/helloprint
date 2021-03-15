<?php

namespace Helloprint\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log extends Logger
{

    public function __construct($channel = 'Helloprint', $path = '/logs/helloprint.log')
    {
        parent::__construct($channel);
        $this->pushHandler(new StreamHandler(__DIR__ .'/../../' .$path));
    }

    public function error($message, array $context = [], \Exception $e = null): void
    {
        if(!empty($e)) {
            $context = array_merge($context, array(
                "file" => $e->getFile(),
                "line"=> $e->getLine(),
                "message" => $e->getMessage()
            ));
        }

        parent::error($message, $context);
    }
}
