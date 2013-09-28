<?php
use DaveJamesMiller\Breadcrumbs;
use Mockery as m;

class IntegrationTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->environment = m::mock('Illuminate\View\Environment');
        $this->manager = new Breadcrumbs\Manager($this->environment);

        $this->manager->register('home', function($breadcrumbs) {
            $breadcrumbs->push('Home', '/');
        });

        $this->manager->register('home', function($breadcrumbs) {
            $breadcrumbs->push('Home', '/');
        });

        $this->manager->register('blog', function($breadcrumbs) {
            $breadcrumbs->parent('home');
            $breadcrumbs->push('Blog', '/blog');
        });

        $this->manager->register('post', function($breadcrumbs, $post) {
            $breadcrumbs->parent('blog');
            $breadcrumbs->push($post->title, '/blog/' . $post->id);
        });
    }

    public function testGenerateHome()
    {
        $breadcrumbs = $this->manager->generate('home');

        $this->assertCount(1, $breadcrumbs);

        $this->assertSame('Home', $breadcrumbs[0]->title);
        $this->assertSame('/', $breadcrumbs[0]->url);
        $this->assertTrue($breadcrumbs[0]->first);
        $this->assertTrue($breadcrumbs[0]->last);
    }

    public function testGenerateBlog()
    {
        $breadcrumbs = $this->manager->generate('blog');

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
        $post = (object) array(
            'id' => 123,
            'title' => 'Sample Post',
        );

        $breadcrumbs = $this->manager->generate('post', $post);

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

    public function testRender()
    {
        // Set a custom view to make sure that view is used
        $viewName = 'my.sample.view';
        $this->manager->setView($viewName);

        // Create a mock view which should be used to render the HTML
        $html = 'some html';
        $view = m::mock('Illuminate\View\View');
        $view->shouldReceive('render')->once()->withNoArgs()->andReturn($html);

        // Make sure the view is created with the correct parameters
        $breadcrumbs = $this->manager->generate('home');
        $vars = array('breadcrumbs' => $breadcrumbs);
        $this->environment->shouldReceive('make')->once()->with($viewName, $vars)->andReturn($view);

        // Make sure the HTML rendered by the view is returned by the manager
        $this->assertSame($html, $this->manager->render('home'));
    }
}
