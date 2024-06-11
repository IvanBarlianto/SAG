<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Example;

class ExampleController extends Controller
{
    public function index()
    {
        $result = Example::doSomething();
        return $result;
    }
}
