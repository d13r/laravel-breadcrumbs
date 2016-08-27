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
     * Generator constructor.
     *
     * @param  \Illuminate\Contracts\Container\Container|null  $container
     */
    public function __construct(ContainerContract $container = null)
    {
        $this->container = $container ?: new Container;
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

		array_unshift($params, $this);

        call_user_func_array(
            $this->createClassCallable($this->callbacks[$name], $this->container),
            $params
        );
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
     *
     * @return array
     */
    protected function createClassCallable($callback, $container)
    {
        if(is_callable($callback)) {
            return $callback;
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

        return [$container->make($class), $method];
    }

    protected function parseClassCallable($callback)
    {
        $segments = explode('@', $callback);

        return [$segments[0], count($segments) == 2 ? $segments[1] : null];
    }

}
