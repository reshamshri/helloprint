<?php
namespace Helloprint\Client;
/**
 * Interface HttpRequest
 * @package Helloprint\Client
 */
interface HttpRequest
{
    /**
     * @return mixed
     */
    public function execute();

    public function getClient();
}
