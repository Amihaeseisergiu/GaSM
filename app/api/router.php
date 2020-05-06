<?php
header("Access-Control-Allow-Headers: *");
header("Content-type: application/json");

$allHeaders = getallheaders();

require_once('./marker-routes.php');

$allRoutes = [];

array_push($allRoutes, ...$markerRoutes);

foreach ($allRoutes as $route) {
    if ($_SERVER["REQUEST_METHOD"] !== $route["method"]) {
        continue;
    }

    $params = matchRouteToActualURL($_SERVER["REQUEST_URI"], $route["url"]);
    if (!isset($params)) {
        continue;
    }

    $route["handler"]([
        "params" => $params
    ]);
    break;
}

function matchRouteToActualURL($url, $route)
{
    $url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
    $route = explode('/', filter_var(rtrim($route, '/'), FILTER_SANITIZE_URL));
    for ($i = 0; $i < 5; $i++) {
        unset($url[$i]);
    }
    unset($route[0]);
    if (count($url) != count($route)) {
        return null;
    }
    $params = [];
    $j = 1;

    for ($i = 5; $i < count($url) + 5; $i++) {
        if ($route[$j][0] == ":") {
            if (is_numeric($url[$i])) {
                $explodedRoute = explode(':', filter_var(rtrim($route[$j], ':'), FILTER_SANITIZE_URL));
                $myArray = array($explodedRoute[1] => intval($url[$i]));
                array_push($params, $myArray);
            } else {
                $explodedRoute = explode(':', filter_var(rtrim($route[$j], ':'), FILTER_SANITIZE_URL));
                $myArray = array($explodedRoute[1] => $url[$i]);
                array_push($params, $myArray);
            }
        } else {
            if (strcmp($url[$i], $route[$j]) != 0) {
                return null;
            }
        }
        $j++;
    }
    return $params;
}
