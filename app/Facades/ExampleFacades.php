<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ExampleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'example-service';
    }
}
