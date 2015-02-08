<?php

use DaveJamesMiller\Breadcrumbs\Manager;
use Mockery as m;

class ManagerTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->factory = m::mock('Illuminate\Contracts\View\Factory');
		$this->router  = m::mock('Illuminate\Contracts\Routing\Registrar');
		$this->manager = new Manager($this->factory, $this->router);

		$this->manager->register('sample', function() {});
	}

	public function testSetView()
	{
		$view = 'my.sample.view';

		$this->manager->setView($view);

		$this->assertSame($view, $this->manager->getView());
	}

	public function testExists()
	{
		$this->assertTrue($this->manager->exists('sample'));
		$this->assertFalse($this->manager->exists('invalid'));

		$this->manager->setCurrentRoute('sample');
		$this->assertTrue($this->manager->exists());

		$this->manager->setCurrentRoute('invalid');
		$this->assertFalse($this->manager->exists());
	}

}
