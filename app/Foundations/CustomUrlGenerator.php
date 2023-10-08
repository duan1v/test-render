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
        if (env('APP_ENV') == 'production') {
            $secure = true;
        }
        return parent::asset($path, $secure);
    }
}
