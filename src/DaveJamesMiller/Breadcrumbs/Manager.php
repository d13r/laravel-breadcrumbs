<?php
namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\View\Environment as ViewEnvironment;

class Manager
{
    protected $callbacks = array();

    protected $environment;

    protected $view;

    public function __construct(ViewEnvironment $environment)
    {
        $this->environment = $environment;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function register($name, $callback)
    {
        $this->callbacks[$name] = $callback;
    }

    public function generate($name)
    {
        $args = array_slice(func_get_args(), 1);

        return $this->generateArray($name, $args);
    }

    public function generateArray($name, $args = array())
    {
        $generator = new Generator($this->callbacks);
        $generator->call($name, $args);
        return $generator->toArray();
    }

    public function render($name)
    {
        $args = array_slice(func_get_args(), 1);

        return $this->renderArray($name, $args);
    }

    public function renderArray($name, $args = array())
    {
        $breadcrumbs = $this->generateArray($name, $args);

        return $this->environment->make($this->view, compact('breadcrumbs'))->render();
    }
}
