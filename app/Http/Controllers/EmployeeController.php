<?php

namespace App\Http\Controllers;

/**
 * Class EmployeeController
 * @package App\Http\Controllers
 * @version 2023/10/16 0016, 1:01
 *
 */
class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.index');
    }
}
