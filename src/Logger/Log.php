<?php

namespace Helloprint\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class Log
 * @package Helloprint\Logger
 */
class Log extends Logger
{

    /**
     * Log constructor.
     * @param string $channel
     * @param string $path
     */
    public function __construct($channel = 'Helloprint', $path = '/logs/helloprint.log')
    {
        parent::__construct($channel);
        $this->pushHandler(new StreamHandler(__DIR__ .'/../../' .$path));
    }

    /**
     * @param string $message
     * @param array $context
     * @param \Exception|null $e
     */
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
