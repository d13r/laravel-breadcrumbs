<?php
use DaveJamesMiller\Breadcrumbs;
use Mockery as m;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->environment = m::mock('Illuminate\View\Environment');
        $this->manager = new Breadcrumbs\Manager($this->environment);
    }

    public function testSetView()
    {
        $view = 'my.sample.view';

        $this->manager->setView($view);

        $this->assertSame($view, $this->manager->getView());
    }
}
