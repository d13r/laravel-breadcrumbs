<?php

use DaveJamesMiller\Breadcrumbs;
use Mockery as m;

class IntegrationTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->breadcrumbs = $this->app['breadcrumbs'];

		$this->breadcrumbs->register('home', function($breadcrumbs) {
			$breadcrumbs->push('Home', '/');
		});

		$this->breadcrumbs->register('home', function($breadcrumbs) {
			$breadcrumbs->push('Home', '/');
		});

		$this->breadcrumbs->register('blog', function($breadcrumbs) {
			$breadcrumbs->parent('home');
			$breadcrumbs->push('Blog', '/blog');
		});

		$this->breadcrumbs->register('post', function($breadcrumbs, $post) {
			$breadcrumbs->parent('blog');
			$breadcrumbs->push($post->title, '/blog/' . $post->id);
		});
	}

	public function testGenerateHome()
	{
		$breadcrumbs = $this->breadcrumbs->generate('home');

		$this->assertCount(1, $breadcrumbs);

		$this->assertSame('Home', $breadcrumbs[0]->title);
		$this->assertSame('/', $breadcrumbs[0]->url);
		$this->assertTrue($breadcrumbs[0]->first);
		$this->assertTrue($breadcrumbs[0]->last);
	}

	public function testGenerateBlog()
	{
		$breadcrumbs = $this->breadcrumbs->generate('blog');

		$this->assertCount(2, $breadcrumbs);

		$this->assertSame('Home', $breadcrumbs[0]->title);
		$this->assertSame('/', $breadcrumbs[0]->url);
		$this->assertTrue($breadcrumbs[0]->first);
		$this->assertFalse($breadcrumbs[0]->last);

		$this->assertSame('Blog', $breadcrumbs[1]->title);
		$this->assertSame('/blog', $breadcrumbs[1]->url);
		$this->assertFalse($breadcrumbs[1]->first);
		$this->assertTrue($breadcrumbs[1]->last);
	}

	public function testGeneratePost()
	{
		$post = (object) [
			'id'    => 123,
			'title' => 'Sample Post',
		];

		$breadcrumbs = $this->breadcrumbs->generate('post', $post);

		$this->assertCount(3, $breadcrumbs);

		$this->assertSame('Home', $breadcrumbs[0]->title);
		$this->assertSame('/', $breadcrumbs[0]->url);
		$this->assertTrue($breadcrumbs[0]->first);
		$this->assertFalse($breadcrumbs[0]->last);

		$this->assertSame('Blog', $breadcrumbs[1]->title);
		$this->assertSame('/blog', $breadcrumbs[1]->url);
		$this->assertFalse($breadcrumbs[1]->first);
		$this->assertFalse($breadcrumbs[1]->last);

		$this->assertSame('Sample Post', $breadcrumbs[2]->title);
		$this->assertSame('/blog/123', $breadcrumbs[2]->url);
		$this->assertFalse($breadcrumbs[2]->first);
		$this->assertTrue($breadcrumbs[2]->last);
	}

	public function testRenderPost()
	{
		$post = (object) [
			'id'    => 123,
			'title' => 'Sample Post',
		];

		$html = $this->breadcrumbs->render('post', $post);
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/fixtures/integration.html', $html);
	}

}
