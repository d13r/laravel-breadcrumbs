<?php
namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Routing\Router;
use Illuminate\View\Environment as ViewEnvironment;

class Manager
{
    protected $environment;
    protected $router;

    protected $callbacks = array();
    protected $view;

    protected $currentRoute;

    public function __construct(ViewEnvironment $environment, Router $router)
    {
        $this->environment = $environment;
        $this->router = $router;
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

    public function exists($name = null)
    {
        if (is_null($name)) {
            try {
                list($name) = $this->currentRoute();
            } catch (Exception $e) {
                return false;
            }
        }

        return isset($this->callbacks[$name]);
    }

    public function render($name = null)
    {
        if (is_null($name))
            return $this->renderCurrent();

        $args = array_slice(func_get_args(), 1);
        return $this->renderArray($name, $args);
    }

    public function renderIfExists($name = null)
    {
        if ($this->exists($name))
            return call_user_func_array(array($this, 'render'), func_get_args());
        else
            return '';
    }

    public function renderArray($name, $args = array())
    {
        $breadcrumbs = $this->generateArray($name, $args);

        return $this->environment->make($this->view, compact('breadcrumbs'))->render();
    }

    public function renderArrayIfExists($name = null)
    {
        if ($this->exists($name))
            return call_user_func_array(array($this, 'renderArray'), func_get_args());
        else
            return '';
    }

    protected function renderCurrent()
    {
        list($name, $parameters) = $this->currentRoute();

        return $this->renderArray($name, $parameters);
    }

    protected function currentRoute()
    {
        if ($this->currentRoute)
            return $this->currentRoute;

        $route = $this->router->current();

        $name = $route->getName();

        if (is_null($name)) {
            $uri = head($route->methods()).' '.$route->uri();
            throw new Exception("The current route ($uri) is not named - please check routes.php for an \"as\" parameter");
        }

        $args = $route->parameters();

        return $this->currentRoute = array($name, $route->parameters());
    }

    public function setCurrentRoute($name)
    {
        $args = array_slice(func_get_args(), 1);

        $this->setCurrentRouteArray($name, $args);
    }

    public function setCurrentRouteArray($name, $args = array())
    {
        $this->currentRoute = array($name, $args);
    }

    public function clearCurrentRoute()
    {
        $this->currentRoute = null;
    }
}
