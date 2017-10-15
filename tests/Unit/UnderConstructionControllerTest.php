<?php

namespace LarsJanssen\UnderConstruction\Test\Unit;

use LarsJanssen\UnderConstruction\Controllers\CodeController;
use LarsJanssen\UnderConstruction\Test\TestCase;
use Mockery as m;

class UnderConstructionControllerTest extends TestCase
{

    protected $arrayConfig;
    protected $config;

    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function tearDown()
    {
        m::close();
    }

    public function setUp()
    {
        $this->arrayConfig = require __DIR__.'/../../config/under-construction.php';
        $this->config = m::mock('Illuminate\Config\Repository');
    }

    public function createController()
    {
        $this->config->shouldReceive('get')->with('under-construction')->once()->andReturn($this->arrayConfig);

        return new CodeController($this->config);
    }

    /** @test */
    public function it_not_activate_Throttle()
    {
        $this->arrayConfig['throttle'] = false;
        $codeController = $this->createController();

        $active = $this->invokeMethod($codeController, 'throttleIsActive');

        $this->assertfalse($active);
    }
}
