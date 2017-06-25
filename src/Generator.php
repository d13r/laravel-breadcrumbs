<?php

namespace DaveJamesMiller\Breadcrumbs;

class Generator
{
    protected $breadcrumbs = [];
    protected $callbacks = [];

    public function generate(array $callbacks, string $name, array $params): array
    {
        $this->breadcrumbs = [];
        $this->callbacks   = $callbacks;

        $this->call($name, $params);

        return $this->toArray();
    }

    protected function call(string $name, array $params) //: void
    {
        if (! isset($this->callbacks[ $name ])) {
            throw new Exception("Breadcrumb not found with name \"{$name}\"");
        }

        array_unshift($params, $this);

        call_user_func_array($this->callbacks[ $name ], $params);
    }

    public function parent(string $name, ...$params) //: void
    {
        $this->call($name, $params);
    }

    public function parentArray(string $name, array $params = []) //: void
    {
        $this->call($name, $params);
    }

    public function push(string $title, string $url = null, array $data = []) //: void
    {
        $this->breadcrumbs[] = (object) array_merge($data, [
            'title' => $title,
            'url'   => $url,
            // These will be altered later where necessary:
            'first' => false,
            'last'  => false,
        ]);
    }

    public function toArray(): array
    {
        $breadcrumbs = $this->breadcrumbs;

        // Add first & last indicators
        if ($breadcrumbs) {
            $breadcrumbs[0]->first                        = true;
            $breadcrumbs[ count($breadcrumbs) - 1 ]->last = true;
        }

        return $breadcrumbs;
    }
}
