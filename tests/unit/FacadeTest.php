<?php

namespace Tests;

use Breadcrumbs;

class FacadeTest extends TestCase
{
    public function testFacade()
    {
        $this->assertInstanceOf(\DaveJamesMiller\Breadcrumbs\Manager::class, Breadcrumbs::getFacadeRoot());
        $this->assertSame($this->app['breadcrumbs'], Breadcrumbs::getFacadeRoot());
    }
}
