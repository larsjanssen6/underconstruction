<?php

namespace LarsJanssen\UnderConstruction\Test\Unit;

use LarsJanssen\UnderConstruction\Test\TestCase;

class UnderConstructionModeTest extends TestCase
{



    /** @test */
    public function it_has_config()
    {

        $this->app['config']->set('under-construction', require __DIR__.'/../../config/under-construction.php');

        $this->assertArrayHasKey('enabled', $this->app['config']['under-construction']);
        $this->assertArrayHasKey('hash', $this->app['config']['under-construction']);
    }

    /** @test */
    public function it_has_view()
    {
        $this->assertFileExists(__DIR__.'/../../resources/views/index.blade.php');
    }
   
}
