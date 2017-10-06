<?php

namespace LarsJanssen\UnderConstruction\Test;

use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use LarsJanssen\UnderConstruction\UnderConstruction;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $config = [];

    public function setup()
    {
        parent::setup();

        $this->registerMiddleWare();

        $this->setUpRoutes($this->app);

        $this->config = $this->app['config']->get('demo-mode');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('under-construction.enabled', true);
    }

    protected function registerMiddleware()
    {
        $this->app[Router::class]->aliasMiddleware('under-construction', UnderConstruction::class);
    }

    /**
     * @param Application $app
     */
    protected function setUpRoutes($app)
    {
        $this->app->get('router')->setRoutes(new RouteCollection());

        Route::any('/test', ['middleware' => 'under-construction', function () {
            return 'production site!';
        }]);
    }
}