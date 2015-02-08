<?php

use DaveJamesMiller\Breadcrumbs\Manager;
use Mockery as m;

class ManagerTest extends TestCase {

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
		$this->manager->register($name, $fn = function() {
			// We're not testing whether the callbacks are executed - see GeneratorTest
			throw new Exception('Callback executed');
		});

		return $fn;
	}

	// Breadcrumbs::exists() -> boolean
	public function testExists()
	{
		$this->currentRoute->shouldReceive('get')->andReturn(['sample', [1, 'blah']]);

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

		$this->currentRoute->shouldReceive('get')->andReturn(['sample', [1, 'blah']]);
		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');;

		$this->assertSame('generated', $this->manager->generate());
	}

	// Breadcrumbs::generate($name) -> array
	public function testGenerate_name()
	{
		$fn = $this->register('sample');

		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [])->once()->andReturn('generated');;

		$this->assertSame('generated', $this->manager->generate('sample'));
	}

	// Breadcrumbs::generate($name, $param1, ...) -> array
	public function testGenerate_name_params()
	{
		$fn = $this->register('sample');

		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');;

		$this->assertSame('generated', $this->manager->generate('sample', 1, 'blah'));
	}

	// Breadcrumbs::generateArray($name, $params) -> array
	public function testGenerateArray_name_params()
	{
		$fn = $this->register('sample');

		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');;

		$this->assertSame('generated', $this->manager->generateArray('sample', [1, 'blah']));
	}

	// Breadcrumbs::generateIfExists() -> array
	public function testGenerateIfExists_existing()
	{
		$fn = $this->register('sample');

		$this->currentRoute->shouldReceive('get')->andReturn(['sample', [1, 'blah']]);
		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');

		$this->assertSame('generated', $this->manager->generateIfExists());
	}

	public function testGenerateIfExists_nonexistant()
	{
		$this->currentRoute->shouldReceive('get')->andReturn(['sample', [1, 'blah']]);
		$this->generator->shouldReceive('generate')->never();

		$this->assertSame([], $this->manager->generateIfExists());
	}

	// Breadcrumbs::generateIfExists($name) -> array
	public function testGenerateIfExists_name_existing()
	{
		$fn = $this->register('sample');

		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [])->once()->andReturn('generated');

		$this->assertSame('generated', $this->manager->generateIfExists('sample'));
	}

	public function testGenerateIfExists_name_nonexistant()
	{
		$this->generator->shouldReceive('generate')->never();

		$this->assertSame([], $this->manager->generateIfExists('sample'));
	}

	// Breadcrumbs::generateIfExists($name, $param1, ...) -> array
	public function testGenerateIfExists_name_params_existing()
	{
		$fn = $this->register('sample');

		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');

		$this->assertSame('generated', $this->manager->generateIfExists('sample', 1, 'blah'));
	}

	public function testGenerateIfExists_name_params_nonexistant()
	{
		$this->generator->shouldReceive('generate')->never();

		$this->assertSame([], $this->manager->generateIfExists('sample', 1, 'blah'));
	}

	// Breadcrumbs::generateArrayIfExists($name, $params) -> array
	public function testGenerateArrayIfExists_name_params_existing()
	{
		$fn = $this->register('sample');

		$this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');

		$this->assertSame('generated', $this->manager->generateArrayIfExists('sample', [1, 'blah']));
	}

	public function testGenerateArrayIfExists_name_params_nonexistant()
	{
		$this->generator->shouldReceive('generate')->never();

		$this->assertSame([], $this->manager->generateArrayIfExists('sample', [1, 'blah']));
	}

    // Breadcrumbs::render() -> array
    public function testRender()
    {
        $fn = $this->register('sample');

        $this->currentRoute->shouldReceive('get')->andReturn(['sample', [1, 'blah']]);
        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');;
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->render());
    }

    // Breadcrumbs::render($name) -> array
    public function testRender_name()
    {
        $fn = $this->register('sample');

        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [])->once()->andReturn('generated');;
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->render('sample'));
    }

    // Breadcrumbs::render($name, $param1, ...) -> array
    public function testRender_name_params()
    {
        $fn = $this->register('sample');

        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');;
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->render('sample', 1, 'blah'));
    }

    // Breadcrumbs::renderArray($name, $params) -> array
    public function testRenderArray_name_params()
    {
        $fn = $this->register('sample');

        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');;
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->renderArray('sample', [1, 'blah']));
    }

    // Breadcrumbs::renderIfExists() -> array
    public function testRenderIfExists_existing()
    {
        $fn = $this->register('sample');

        $this->currentRoute->shouldReceive('get')->andReturn(['sample', [1, 'blah']]);
        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->renderIfExists());
    }

    public function testRenderIfExists_nonexistant()
    {
        $this->currentRoute->shouldReceive('get')->andReturn(['sample', [1, 'blah']]);
        $this->generator->shouldReceive('generate')->never();
        $this->view->shouldReceive('render')->never();

        $this->assertSame('', $this->manager->renderIfExists());
    }

    // Breadcrumbs::renderIfExists($name) -> array
    public function testRenderIfExists_name_existing()
    {
        $fn = $this->register('sample');

        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [])->once()->andReturn('generated');
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->renderIfExists('sample'));
    }

    public function testRenderIfExists_name_nonexistant()
    {
        $this->generator->shouldReceive('generate')->never();
        $this->view->shouldReceive('render')->never();

        $this->assertSame('', $this->manager->renderIfExists('sample'));
    }

    // Breadcrumbs::renderIfExists($name, $param1, ...) -> array
    public function testRenderIfExists_name_params_existing()
    {
        $fn = $this->register('sample');

        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->renderIfExists('sample', 1, 'blah'));
    }

    public function testRenderIfExists_name_params_nonexistant()
    {
        $this->generator->shouldReceive('generate')->never();
        $this->view->shouldReceive('render')->never();

        $this->assertSame('', $this->manager->renderIfExists('sample', 1, 'blah'));
    }

    // Breadcrumbs::renderArrayIfExists($name, $params) -> array
    public function testRenderArrayIfExists_name_params_existing()
    {
        $fn = $this->register('sample');

        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');
        $this->view->shouldReceive('render')->with('view', 'generated')->once()->andReturn('rendered');

        $this->assertSame('rendered', $this->manager->renderArrayIfExists('sample', [1, 'blah']));
    }

    public function testRenderArrayIfExists_name_params_nonexistant()
    {
        $this->generator->shouldReceive('generate')->never();
        $this->view->shouldReceive('render')->never();

        $this->assertSame('', $this->manager->renderArrayIfExists('sample', [1, 'blah']));
    }

	// Breadcrumbs::setCurrentRoute($name)
	public function testSetCurrentRoute_name()
	{
		$this->currentRoute->shouldReceive('set')->with('sample', [])->once();

		$this->manager->setCurrentRoute('sample');
	}

	// Breadcrumbs::setCurrentRoute($name, $param1, ...)
	public function testSetCurrentRoute_name_params()
	{
		$this->currentRoute->shouldReceive('set')->with('sample', [1, 'blah'])->once();

		$this->manager->setCurrentRoute('sample', 1, 'blah');
	}

	// Breadcrumbs::setCurrentRouteArray($name, $params)
	public function testSetCurrentRouteArray_name_params()
	{
		$this->currentRoute->shouldReceive('set')->with('sample', [1, 'blah'])->once();

		$this->manager->setCurrentRouteArray('sample', [1, 'blah']);
	}

	// Breadcrumbs::clearCurrentRoute()
	public function testClearCurrentRoute()
	{
		$this->currentRoute->shouldReceive('clear')->withNoArgs()->once();

		$this->manager->clearCurrentRoute();
	}

	// Breadcrumbs::setView($view)
	public function testSetView()
	{
        $fn = $this->register('sample');

        $this->generator->shouldReceive('generate')->with(['sample' => $fn], 'sample', [1, 'blah'])->once()->andReturn('generated');;
        $this->view->shouldReceive('render')->with('custom.view', 'generated')->once()->andReturn('rendered');

		$this->manager->setView($view = 'custom.view');
        $this->assertSame('rendered', $this->manager->render('sample', 1, 'blah'));
	}

}
