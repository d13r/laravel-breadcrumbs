<?php

class ViewTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->breadcrumbs = [
			(object) [
				'title' => 'Home',
				'url'   => '/',
				'first' => true,
				'last'  => false,
			],
			(object) [
				'title' => 'Not a link',
				'url'   => null, // Test non-links
				'first' => false,
				'last'  => false,
			],
			(object) [
				'title' => 'Blog & < >', // Test HTML escaping
				'url'   => '/blog',
				'first' => false,
				'last'  => false,
			],
			(object) [
				'title' => 'Sample Post',
				'url'   => '/blog/123',
				'first' => false,
				'last'  => true,
			],
		];
	}

	public function testBootstrap2()
	{
		$html = View::make('breadcrumbs::bootstrap2', ['breadcrumbs' => $this->breadcrumbs])->render();
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/bootstrap2.html', $html);
	}

	public function testBootstrap3()
	{
		$html = View::make('breadcrumbs::bootstrap3', ['breadcrumbs' => $this->breadcrumbs])->render();
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/bootstrap3.html', $html);
	}

}
