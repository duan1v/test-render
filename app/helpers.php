<?php
namespace App;

function getClientIp()
{
    if (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("HTTP_X_FORWARDED")) {
        $ip = getenv("HTTP_X_FORWARDED");
    } else if (getenv("HTTP_FORWARDED_FOR")) {
        $ip = getenv("HTTP_FORWARDED_FOR");
    } else if (getenv("HTTP_FORWARDED")) {
        $ip = getenv("HTTP_FORWARDED");
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }

    return $ip;
}

function setInitValueWithCookie($cookieName, $value, $isArr = true)
{
    $cookie = data_get($_COOKIE, $cookieName);
    return $cookie ? ($isArr ? json_decode($cookie, true) : $cookie) : $value;
}

function pseudocodeFormat($code)
{
    return preg_replace(['/[\n\s]+(then )/','/[\n\s]+(else )/'], ["\n$1","\n$1"], $code);
}
