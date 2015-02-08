<?php

class ViewTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->view = app('DaveJamesMiller\Breadcrumbs\View');

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
		$html = $this->view->render('breadcrumbs::bootstrap2', $this->breadcrumbs);
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/bootstrap2.html', $html);
	}

	public function testBootstrap3()
	{
		$html = $this->view->render('breadcrumbs::bootstrap3', $this->breadcrumbs);
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/bootstrap3.html', $html);
	}

}
