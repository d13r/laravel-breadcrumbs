<?php
namespace DaveJamesMiller\Breadcrumbs;

class Generator
{
    protected $callbacks = array();
    protected $breadcrumbs = array();

    public function __construct(array $callbacks)
    {
        $this->callbacks = $callbacks;
    }

    public function get()
    {
        return $this->breadcrumbs;
    }

    public function set(array $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    public function call($name, $args)
    {
        if (!isset($this->callbacks[$name]))
            throw new Exception("Invalid breadcrumb: $name");

        array_unshift($args, $this);

        call_user_func_array($this->callbacks[$name], $args);
    }

    public function parent($name)
    {
        $args = array_slice(func_get_args(), 1);

        $this->parentArray($name, $args);
    }

    // This does the same as call() but is named differently for clarity.
    // parent() / parentArray() are used when defining breadcrumbs.
    // call() is used when outputting breadcrumbs.
    public function parentArray($name, $args = array())
    {
        $this->call($name, $args);
    }

    public function push($title, $url = null)
    {
        $this->breadcrumbs[] = (object) array(
            'title' => $title,
            'url' => $url,
            // These will be altered later where necessary:
            'first' => false,
            'last' => false,
        );
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

}
