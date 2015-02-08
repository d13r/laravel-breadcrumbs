<?php

class FacadeTest extends TestCase {

	public function testFacade()
	{
		$this->assertSame(Breadcrumbs::getFacadeRoot(), $this->app['breadcrumbs']);
	}


}
