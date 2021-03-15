<?php

/*
 * The method will return config value based on the provided key.
 * If key not found, it will return default value .
 * If default value not present it will return null, when key not found.
 *
 * @return string|array|null
 * */
function config(string $key, $default = null)
{
    $config = \Helloprint\Config::buildConfig();

    if (strpos($key, \Helloprint\Config::SEPARATOR)) {
        list($parent, $child) = explode(\Helloprint\Config::SEPARATOR, $key);

        return $config[$parent][$child] ?? $default;
    } else if (array_key_exists($key, $config)) {

        return $config[$key];
    } else {

        return $default;
    }
}

/*
 * Helper method to generate random unique token
 * */
function getToken(): string
{
    return bin2hex(random_bytes(20));
}




