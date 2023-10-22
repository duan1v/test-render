<?php

namespace App\Http\Controllers;

/**
 * Class MigrateController
 * @package App\Http\Controllers
 * @version 2023/10/20 0020, 23:11
 *
 */
class MigrateController extends Controller
{
    public function index()
    {
        return view('migrate.index');
    }
}
