<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
use DaveJamesMiller\Breadcrumbs\Manager;
use Illuminate\Support\HtmlString;
use LogicException;
use Mockery as m;
use DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException;

class ManagerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->currentRoute = m::mock('DaveJamesMiller\Breadcrumbs\CurrentRoute');
        $this->generator    = m::mock('DaveJamesMiller\Breadcrumbs\Generator');
        $this->view         = m::mock('DaveJamesMiller\Breadcrumbs\View');
        $this->manager      = new Manager($this->currentRoute, $this->generator, $this->view);

        $this->manager->setView('view');
    }

    // Breadcrumbs::register($name, $callback) is tested by other methods
    protected function register($name)
    {
        $this->manager->register($name, $fn = function () {
            // We're not testing whether the callbacks are executed - see GeneratorTest
            throw new LogicException('Callback executed');
        });

        return $fn;
    }

    // Breadcrumbs::exists() -> boolean
    public function testExists()
    {
        $this->currentRoute
            ->shouldReceive('get')
            ->andReturn(['sample', [1, 'blah']]);

        $this->assertFalse($this->manager->exists());

        $this->register('sample');
        $this->assertTrue($this->manager->exists());
    }

    // Breadcrumbs::exists($name) -> boolean
    public function testExists_name()
    {
        $this->assertFalse($this->manager->exists('sample'));

        $this->register('sample');
        $this->assertTrue($this->manager->exists('sample'));
    }

    // Breadcrumbs::generate() -> array
    public function testGenerate()
    {
        $fn = $this->register('sample');

        $this->currentRoute
            ->shouldReceive('get')
            ->andReturn(['sample', [1, 'blah']]);
        $this->generator
            ->shouldReceive('generate')
            ->with(['sample' => $fn], 'sample', [1, 'blah'])
            ->once()
            ->andReturn(['generated']);;

        $this->assertSame(['generated'], $this->manager->generate());
    }

    // Breadcrumbs::generate($name) -> array
    public function testGenerate_name()
    {
        $fn = $this->register('sample');

        $this->generator
            ->shouldReceive('generate')
            ->with(['sample' => $fn], 'sample', [])
            ->once()
            ->andReturn(['generated']);;

        $this->assertSame(['generated'], $this->manager->generate('sample'));
    }

    // Breadcrumbs::generate($name, $param1, ...) -> array
    public function testGenerate_name_params()
    {
        $fn = $this->register('sample');

        $this->generator
            ->shouldReceive('generate')
            ->with(['sample' => $fn], 'sample', [1, 'blah'])
            ->once()
            ->andReturn(['generated']);;

        $this->assertSame(['generated'], $this->manager->generate('sample', 1, 'blah'));
    }

    // Breadcrumbs::render() -> array
    public function testRender()
    {
        $fn = $this->register('sample');

        $this->currentRoute
            ->shouldReceive('get')
            ->andReturn(['sample', [1, 'blah']]);
        $this->generator
            ->shouldReceive('generate')
            ->with(['sample' => $fn], 'sample', [1, 'blah'])
            ->once()
            ->andReturn(['generated']);;
        $this->view
            ->shouldReceive('render')
            ->with('view', ['generated'])
            ->once()
            ->andReturn(new HtmlString('rendered'));

        $this->assertSame('rendered', $this->manager->render()->toHtml());
    }

    // Breadcrumbs::render($name) -> array
    public function testRender_name()
    {
        $fn = $this->register('sample');

        $this->generator
            ->shouldReceive('generate')
            ->with(['sample' => $fn], 'sample', [])
            ->once()
            ->andReturn(['generated']);;
        $this->view
            ->shouldReceive('render')
            ->with('view', ['generated'])
            ->once()
            ->andReturn(new HtmlString('rendered'));

        $this->assertSame('rendered', $this->manager->render('sample')->toHtml());
    }

    // Breadcrumbs::render($name, $param1, ...) -> array
    public function testRender_name_params()
    {
        $fn = $this->register('sample');

        $this->generator
            ->shouldReceive('generate')
            ->with(['sample' => $fn], 'sample', [1, 'blah'])
            ->once()
            ->andReturn(['generated']);;
        $this->view
            ->shouldReceive('render')
            ->with('view', ['generated'])
            ->once()
            ->andReturn(new HtmlString('rendered'));

        $this->assertSame('rendered', $this->manager->render('sample', 1, 'blah')->toHtml());
    }

    // Breadcrumbs::setCurrentRoute($name)
    public function testSetCurrentRoute_name()
    {
        $this->currentRoute
            ->shouldReceive('set')
            ->with('sample', [])
            ->once();

        $this->manager->setCurrentRoute('sample');
    }

    // Breadcrumbs::setCurrentRoute($name, $param1, ...)
    public function testSetCurrentRoute_name_params()
    {
        $this->currentRoute
            ->shouldReceive('set')
            ->with('sample', [1, 'blah'])
            ->once();

        $this->manager->setCurrentRoute('sample', 1, 'blah');
    }

    // Breadcrumbs::clearCurrentRoute()
    public function testClearCurrentRoute()
    {
        $this->currentRoute
            ->shouldReceive('clear')->withNoArgs()
            ->once();

        $this->manager->clearCurrentRoute();
    }

    // Breadcrumbs::setView($view)
    public function testSetView()
    {
        $fn = $this->register('sample');

        $this->generator
            ->shouldReceive('generate')
            ->with(['sample' => $fn], 'sample', [1, 'blah'])
            ->once()
            ->andReturn(['generated']);;
        $this->view
            ->shouldReceive('render')
            ->with('custom.view', ['generated'])
            ->once()
            ->andReturn(new HtmlString('rendered'));

        $this->manager->setView($view = 'custom.view');
        $this->assertSame('rendered', $this->manager->render('sample', 1, 'blah')->toHtml());
    }
}
