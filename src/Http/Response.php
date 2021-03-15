<?php

namespace Helloprint\Http;

use Helloprint\Logger\Log;
use Symfony\Component\HttpFoundation\Response as Handler;


/**
 * Class Response
 * @package Helloprint\Http
 */
class Response
{
    private Log $log;

    /**
     * Response constructor.
     * @param string $channel
     */
    public function __construct(string $channel='Http')
    {
        $this->log = new Log($channel);
    }

    /**
     * @param \Exception | \TypeError $e
     */
    public function exceptionHandler( $e )
    {
        $response = $this->handlerInstance( [
            "errors" => [
                    "message" => $e->getMessage(),
                ],
            ],  Handler::HTTP_BAD_REQUEST );

        $this->log->error( $e->getMessage(), ["file" => $e->getFile(), "line" => $e->getLine() ]);
        $response->send();
        die();
    }

    /**
     * @param $payload
     */
    public function sendJsonResponse($payload )
    {
        $response = $this->handlerInstance($payload, Handler::HTTP_OK);
        $response->send();
        die();
    }

    /**
     * @param $payload
     * @param int $statusCode
     * @return Handler
     */
    private function handlerInstance($payload, int $statusCode): Handler
    {
        return new Handler(
            json_encode( [ "data" => $payload ]),
            $statusCode,
            ['content-type' => 'application/json']
        );
    }
}
