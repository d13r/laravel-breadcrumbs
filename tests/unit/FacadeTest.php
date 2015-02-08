<?php

class FacadeTest extends TestCase {

	public function testFacade()
	{
		$this->assertInstanceOf('DaveJamesMiller\Breadcrumbs\Manager', Breadcrumbs::getFacadeRoot());
		$this->assertSame($this->app['breadcrumbs'], Breadcrumbs::getFacadeRoot());
	}

}
