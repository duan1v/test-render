<?php

namespace App\Foundations;

use Illuminate\Routing\UrlGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class CustomUrlGenerator
 * @package App\Foundations
 * @version 2023/10/09 0009, 0:19
 *
 */
class CustomUrlGenerator extends UrlGenerator
{
    public function asset($path, $secure = null)
    {
        if (env('APP_ENV') == 'production') {
            $secure = true;
        }
        return parent::asset($path, $secure);
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        $route = parent::route($name, $parameters = [], $absolute = true);
        if (env('APP_ENV') != 'local') {
            $route = preg_replace('/^http:/i', 'https:', $route);
        }
        return $route;
    }
}
