<?php
use DaveJamesMiller\Breadcrumbs;
use Mockery as m;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->environment = m::mock('Illuminate\View\Environment');
        $this->router = m::mock('Illuminate\Routing\Router');
        $this->manager = new Breadcrumbs\Manager($this->environment, $this->router);
    }

    public function testSetView()
    {
        $view = 'my.sample.view';

        $this->manager->setView($view);

        $this->assertSame($view, $this->manager->getView());
    }
}
