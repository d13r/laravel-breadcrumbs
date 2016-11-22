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

    public function call($name, $params)
    {
        if (!isset($this->callbacks[$name]))
            throw new Exception("Breadcrumb not found with name \"{$name}\"");

        array_unshift($params, $this);

        call_user_func_array($this->callbacks[$name], $params);
    }

    public function parent($name)
    {
        $params = array_slice(func_get_args(), 1);

        $this->parentArray($name, $params);
    }

    // This does the same as call() but is named differently for clarity.
    // parent() / parentArray() are used when defining breadcrumbs.
    // call() is used when outputting breadcrumbs.
    public function parentArray($name, $params = array())
    {
        $this->call($name, $params);
    }

    public function push($title, $url = null, array $data = array())
    {
        $this->breadcrumbs[] = (object) array_merge($data, array(
            'title' => $title,
            'url' => $url,
            // These will be altered later where necessary:
            'first' => false,
            'last' => false,
        ));
    }

    public function unshift($title, $url = null, array $data = array())
    {
        array_unshift($this->breadcrumbs, (object) array_merge($data, array(
          'title' => $title,
          'url' => $url,
          // These will be altered later where necessary:
          'first' => false,
          'last' => false,
        )));
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
