<?php

namespace Helloprint\Http;

use Symfony\Component\HttpFoundation\Request;


/**
 * The class handled GET , POST request methods
 * When no route matches it will throw 404 exception
*/
class Route
{
    /**
     * @param $name
     * @param $arguments
     * @throws RouteNotFoundException
     */
    public static function __callStatic($name, $arguments)
    {
        $request = Request::createFromGlobals();
        if(!empty($arguments)) {
            list ($uri, $action) = $arguments;

            if ($uri == $request->getPathInfo() && $request->getMethod() === strtoupper($name)) {
                list($class, $method) = explode('@', $action);
                $result = call_user_func(array(new $class, $method), $request);
                $response = new Response();
                $response->sendJsonResponse($result);
            }
        }

        if($name == 'notfound') {
                throw new RouteNotFoundException(
                    sprintf(
                        'The request route %s not found.',
                        $request->getPathInfo()
                    ), 404
                );
        }
    }
}
