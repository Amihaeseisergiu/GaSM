<?php
require_once('./marker-routes.php');
require_once('./campaign-routes.php');
require_once('../config/Response.php');

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");

$allHeaders = getallheaders();

$allRoutes = [
    ... $markerRoutes,
    ... $campaignRoutes
];

foreach ($allRoutes as $routeConfig) {
    if (parseRequest($routeConfig)) {
        exit;
    }
}

handle404();

function parseRequest($routeConfig)
{
    $url = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method !== $routeConfig['method']) {
        return false;
    }

    $regExpString = routeExpToRegExp($routeConfig['route']);

    if (preg_match("/$regExpString/", $url, $matches)) {

        $params = [];
        $parts = explode('/', $routeConfig['route']);

        $index = 1;
        foreach ($parts as $p) {
            if (!empty($p) && $p[0] === ':') {
                $params[substr($p, 1)] = $matches[$index];
                $index++;
            }
        }

        $payload = file_get_contents('php://input');

        if (strlen($payload)) {
            $payload = json_decode($payload, true);
        } else {
            $payload = NULL;
        }

        call_user_func($routeConfig['handler'], [
            "params" => $params,
            "payload" => $payload
        ]);

        return true;
    }

    return false;
}

function handle404()
{
    Response::status(404);
}



function routeExpToRegExp($route)
{
    $regExpString = "";
    $parts = explode('/', $route);

    foreach ($parts as $p) {
        $regExpString .= '\/';

        if (!empty($p) && $p[0] === ":") {
            $regExpString .= '([a-zA-Z0-9]+)';
        } else {
            $regExpString .= $p;
        }
    }
    $regExpString .= '$';

    return $regExpString;
}