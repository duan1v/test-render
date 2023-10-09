<?php

namespace App\Foundations;

use Illuminate\Routing\UrlGenerator;

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
        return parent::asset($path, true);
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        $route = parent::route($name, $parameters, $absolute);
        return preg_replace('/^http:/i', 'https:', $route);
    }
}
