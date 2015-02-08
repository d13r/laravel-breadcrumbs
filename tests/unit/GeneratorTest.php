<?php

use DaveJamesMiller\Breadcrumbs\Generator;
use Mockery as m;

class GeneratorTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->generator = new Generator;
	}

	public function testCallbacks()
	{
		$this->generator->generate([
			'sample' => function($breadcrumbs)
			{
				$this->assertSame($this->generator, $breadcrumbs);
			},
		], 'sample', []);
	}

	public function testCallbackParameters()
	{
		$this->generator->generate([
			'sample' => function($breadcrumbs, $num, $text)
			{
				$this->assertSame(1,      $num);
				$this->assertSame('blah', $text);
			},
		], 'sample', [1, 'blah']);
	}

	// $breadcrumbs->push($title)
	// $breadcrumb->title
	public function testPush_title()
	{
		$breadcrumbs = $this->generator->generate([
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->push('Home');
			},
		], 'sample', []);

		$this->assertCount(1,     $breadcrumbs);
		$this->assertSame('Home', $breadcrumbs[0]->title);
		$this->assertNull($breadcrumbs[0]->url);
	}

	// $breadcrumbs->push($title, $url)
	// $breadcrumb->url
	public function testPush_title_url()
	{
		$breadcrumbs = $this->generator->generate([
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->push('Home', '/');
			},
		], 'sample', []);

		$this->assertCount(1,     $breadcrumbs);
		$this->assertSame('Home', $breadcrumbs[0]->title);
		$this->assertSame('/',    $breadcrumbs[0]->url);
	}

	// $breadcrumbs->push($title, $url, $data)
	// $breadcrumb->custom_attribute_name
	public function testPush_title_url_data()
	{
		$data = [
			'foo'   => 'bar',
			'baz'   => 'qux',
			'title' => 'should not be overwritten by custom data',
		];

		$breadcrumbs = $this->generator->generate([
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->push('Home', '/', ['foo' => 'bar', 'title' => 'ignored']);
			},
		], 'sample', []);

		$this->assertCount(1,     $breadcrumbs);
		$this->assertSame('Home', $breadcrumbs[0]->title);
		$this->assertSame('/',    $breadcrumbs[0]->url);
		$this->assertSame('bar',  $breadcrumbs[0]->foo);
	}

	public function testPushMultipleTimes()
	{
		$breadcrumbs = $this->generator->generate([
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->push('Level 1', '/1');
				$breadcrumbs->push('Level 2', '/2');
				$breadcrumbs->push('Level 3', '/3');
			},
		], 'sample', []);

		$this->assertCount(3,        $breadcrumbs);
		$this->assertSame('Level 1', $breadcrumbs[0]->title);
		$this->assertSame('Level 2', $breadcrumbs[1]->title);
		$this->assertSame('Level 3', $breadcrumbs[2]->title);
		$this->assertSame('/1',      $breadcrumbs[0]->url);
		$this->assertSame('/2',      $breadcrumbs[1]->url);
		$this->assertSame('/3',      $breadcrumbs[2]->url);
	}

	// $breadcrumbs->parent($name)
	public function testParent_name()
	{
		$breadcrumbs = $this->generator->generate([
			'home' => function($breadcrumbs)
			{
				$breadcrumbs->push('Home', '/');
			},
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->parent('home');
				$breadcrumbs->push('Page', '/page');
			},
		], 'sample', []);

		$this->assertCount(2,      $breadcrumbs);
		$this->assertSame('Home',  $breadcrumbs[0]->title);
		$this->assertSame('/',     $breadcrumbs[0]->url);
		$this->assertSame('Page',  $breadcrumbs[1]->title);
		$this->assertSame('/page', $breadcrumbs[1]->url);
	}

	// $breadcrumbs->parent($name, $param1, ...)
	public function testParent_name_params()
	{
		$breadcrumbs = $this->generator->generate([
			'parent' => function($breadcrumbs, $num, $text)
			{
				$this->assertSame(1,      $num);
				$this->assertSame('blah', $text);
			},
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->parent('parent', 1, 'blah');
			},
		], 'sample', []);
	}

	// $breadcrumbs->parentArray($name, $params)
	public function testParentArray_name_params()
	{
		$breadcrumbs = $this->generator->generate([
			'parent' => function($breadcrumbs, $num, $text)
			{
				$this->assertSame(1,      $num);
				$this->assertSame('blah', $text);
			},
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->parentArray('parent', [1, 'blah']);
			},
		], 'sample', []);
	}

	// $breadcrumb->first
	// $breadcrumb->last
	public function testFirstLast()
	{
		$breadcrumbs = $this->generator->generate([
			'sample' => function($breadcrumbs)
			{
				$breadcrumbs->push('Level 1', '/1');
				$breadcrumbs->push('Level 2', '/2');
				$breadcrumbs->push('Level 3', '/3');
			},
		], 'sample', []);

		$this->assertCount(3, $breadcrumbs);

		$this->assertTrue($breadcrumbs[0]->first, '$breadcrumbs[0]->first');
		$this->assertFalse($breadcrumbs[1]->first, '$breadcrumbs[1]->first');
		$this->assertFalse($breadcrumbs[2]->first, '$breadcrumbs[2]->first');

		$this->assertFalse($breadcrumbs[0]->last, '$breadcrumbs[0]->last');
		$this->assertFalse($breadcrumbs[1]->last, '$breadcrumbs[1]->last');
		$this->assertTrue($breadcrumbs[2]->last, '$breadcrumbs[2]->last');
	}

}
