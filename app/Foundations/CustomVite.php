<?php

namespace App\Foundations;

use Illuminate\Foundation\Vite;
use Illuminate\Routing\UrlGenerator;

/**
 * Class CustomUrlGenerator
 * @package App\Foundations
 * @version 2023/10/09 0009, 0:19
 *
 */
class CustomVite extends Vite
{
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    protected function assetPath($path, $secure = null)
    {
        if (env('APP_ENV') == 'production') {
            $secure = true;
        }
        return asset($path, true);
    }
}
