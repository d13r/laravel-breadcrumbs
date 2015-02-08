<?php

class IntegrationTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		Breadcrumbs::register('home', function($breadcrumbs) {
			$breadcrumbs->push('Home', '/');
		});

		Breadcrumbs::register('category', function($breadcrumbs, $category) {
			$breadcrumbs->parent('home');
			$breadcrumbs->push($category->title, '/category/' . $category->id);
		});

		Breadcrumbs::register('post', function($breadcrumbs, $post) {
			$breadcrumbs->parent('category', $post->category);
			$breadcrumbs->push($post->title, '/blog/' . $post->id);
		});

		$this->post = (object) [
			'id'       => 123,
			'title'    => 'Sample Post',
			'category' => (object) [
				'id'    => 456,
				'title' => 'Sample Category',
			],
		];
	}

	public function testGenerate()
	{
		$breadcrumbs = Breadcrumbs::generate('post', $this->post);

		$this->assertCount(3, $breadcrumbs);

		$this->assertSame('Home', $breadcrumbs[0]->title);
		$this->assertSame('/', $breadcrumbs[0]->url);
		$this->assertTrue($breadcrumbs[0]->first);
		$this->assertFalse($breadcrumbs[0]->last);

		$this->assertSame('Sample Category', $breadcrumbs[1]->title);
		$this->assertSame('/category/456', $breadcrumbs[1]->url);
		$this->assertFalse($breadcrumbs[1]->first);
		$this->assertFalse($breadcrumbs[1]->last);

		$this->assertSame('Sample Post', $breadcrumbs[2]->title);
		$this->assertSame('/blog/123', $breadcrumbs[2]->url);
		$this->assertFalse($breadcrumbs[2]->first);
		$this->assertTrue($breadcrumbs[2]->last);
	}

	public function testRender()
	{
		$html = Breadcrumbs::render('post', $this->post);
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/integration.html', $html);
	}

}
