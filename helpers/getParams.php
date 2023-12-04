<?php
function getParams($param)
{
    $urlAll = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $parts = parse_url( $urlAll );
    parse_str( $parts['query'] , $query );
    return $query["$param"];
}