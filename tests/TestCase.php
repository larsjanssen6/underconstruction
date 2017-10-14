<?php

namespace LarsJanssen\UnderConstruction\Test;

use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use LarsJanssen\UnderConstruction\UnderConstruction;
use LarsJanssen\UnderConstruction\UnderConstructionServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $config = [];

    public function setup()
    {
        parent::setup();

        $this->registerMiddleWare();

        $this->setUpRoutes();

        $this->registerServiceProvider();

        $this->app->useEnvironmentPath(getcwd() . '/tests');

        $this->config = $this->app['config']->get('under-construction');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('under-construction.enabled', true);

        $app['config']->set('under-construction.title', 'title');

        $app['config']->set('under-construction.back-button', 'back-button');

        $app['config']->set('under-construction.hash', 9999);
    }

    protected function registerMiddleware()
    {
        $this->app[Router::class]->aliasMiddleware('under-construction', UnderConstruction::class);
    }

    protected function registerServiceProvider()
    {
        $this->app->register(UnderConstructionServiceProvider::class);
    }

    protected function setUpRoutes()
    {
        $this->app->get('router')->setRoutes(new RouteCollection());

        Route::any('/test', ['middleware' => 'under-construction', function () {
            return 'production site!';
        }]);
    }
}
