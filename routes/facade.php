<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller as BaseController;

// ServiceProvider
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('example-service', function ($app) {
            return new ExampleService();
        });
    }

    public function boot()
    {
        //
    }
}

// Service
class ExampleService
{
    public function doSomething()
    {
        return "Doing something!";
    }
}

// Facade
class ExampleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'example-service';
    }
}

// Controller
class ExampleController extends BaseController
{
    public function index()
    {
        $result = ExampleFacade::doSomething();
        return $result;
    }
}

// Register Service Provider
app()->register(AppServiceProvider::class);

// Register Facade Alias
class_alias(ExampleFacade::class, 'Example');

// Define Route
Route::get('/example', [ExampleController::class, 'index']);

// Simulate a request to the route (for testing purposes)
$response = Route::dispatch(
    \Illuminate\Http\Request::create('/example', 'GET')
);

echo $response->getContent();
