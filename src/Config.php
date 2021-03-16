<?php

namespace Helloprint;

class Config
{
    public const SEPARATOR = '.';
    public const DEFAULT_CONFIG_PATH = __DIR__.'/config';
    public static array $configuration = [];

    /**
     * @return array
     */
    public static function buildConfig(): array
    {
        if(!empty(self::$configuration)) {
            return self::$configuration;
        }

        self::$configuration = require_once self::DEFAULT_CONFIG_PATH.'/app.php';

        return self::$configuration;
    }
}
