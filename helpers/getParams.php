<?php
function getParams($param)
{
    $urlAll = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $parts = parse_url( $urlAll );
    parse_str( $parts['query'] , $query );
    return $query["$param"];
}
function getParamsForRepetition($param)
{
    $urlAll = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $parts = parse_url($urlAll);
    parse_str($parts['query'], $query);

    if (isset($query[$param])) {
        $paramValue = is_array($query[$param]) ? $query[$param] : [$query[$param]];

        // обрезаю и нормализую
        $paramValues = array_map('htmlspecialchars', array_map('trim', $paramValue));
        return $paramValues;
    } else {
        return [];
    }
}