<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\Manager;

class FacadeTest extends TestCase
{
    public function testFacade()
    {
        $this->assertInstanceOf(Manager::class, Breadcrumbs::getFacadeRoot());
        $this->assertSame($this->app->make(Manager::class), Breadcrumbs::getFacadeRoot());
    }
}
