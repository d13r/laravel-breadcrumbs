<?php namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Str;
use UnexpectedValueException;

class Generator {

	protected $breadcrumbs = [];
	protected $callbacks   = [];

    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Namespace
     *
     * @var String
     */
    protected $namespace;

    /**
     * Generator constructor.
     *
     * @param  \Illuminate\Contracts\Container\Container|null $container
     * @param String $namespace
     */
    public function __construct(ContainerContract $container = null, $namespace)
    {
        $this->container = $container ?: new Container;
        $this->namespace = $namespace;
    }

    public function generate(array $callbacks, $name, $params)
	{
		$this->breadcrumbs = [];
		$this->callbacks   = $callbacks;

		$this->call($name, $params);
		return $this->toArray();
	}

	protected function call($name, $params)
	{
		if (!isset($this->callbacks[$name]))
			throw new Exception("Breadcrumb not found with name \"{$name}\"");

        list($function, $param_arr) = $this->createClassCallable($this->callbacks[$name], $this->container, $params);

        call_user_func_array($function, $param_arr);
	}

	public function parent($name)
	{
		$params = array_slice(func_get_args(), 1);

		$this->call($name, $params);
	}

	public function parentArray($name, $params = [])
	{
		$this->call($name, $params);
	}

	public function push($title, $url = null, array $data = [])
	{
		$this->breadcrumbs[] = (object) array_merge($data, [
			'title' => $title,
			'url' => $url,
			// These will be altered later where necessary:
			'first' => false,
			'last' => false,
		]);
	}

	public function toArray()
	{
		$breadcrumbs = $this->breadcrumbs;

		// Add first & last indicators
		if ($breadcrumbs) {
			$breadcrumbs[0]->first = true;
			$breadcrumbs[count($breadcrumbs) - 1]->last = true;
		}

		return $breadcrumbs;
	}

    /**
     * @param mixed $callback
     * @param ContainerContract $container
     * @param array $param_arr
     *
     * @return array
     */
    protected function createClassCallable($callback, $container, $param_arr)
    {
        array_unshift($param_arr, $this);

        if(is_callable($callback)) {
            return [$callback, $param_arr];
        }

        if( ! is_string($callback)) {
            throw new UnexpectedValueException(sprintf(
                'Invalid breadcrumbs callback: [%s]', $callback
            ));
        }

        if( ! Str::contains($callback, '@')) {
            $callback .= '@__invoke';
        }

        list($class, $method) = $this->parseClassCallable($callback);

        if( ! method_exists($class, $method)) {
            throw new UnexpectedValueException(sprintf(
                'Invalid breadcrumbs callback: [%s]', $callback
            ));
        }

        $parameters = [];

        if(is_subclass_of($class, 'DaveJamesMiller\Breadcrumbs\Breadcrumbs')) {
            array_shift($param_arr);

            $parameters = [$this];
        }

        return [[$container->make($class, $parameters), $method], $param_arr];
    }

    protected function parseClassCallable($callback)
    {
        $segments = explode('@', $callback);
        $class = $segments[0];

        if (!empty($this->namespace) && !starts_with($class, '\\')) {
            $class = str_finish($this->namespace, '\\') . $class;
        }

        return [$class, count($segments) == 2 ? $segments[1] : null];
    }

}
