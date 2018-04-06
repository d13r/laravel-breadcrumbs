<?php namespace App;

class Category
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __get($var)
    {
        $method = 'get' . ucfirst($var);

        return $this->$method();
    }

    public function getTitle()
    {
        return 'Category ' . $this->id;
    }

    public function getParent()
    {
        if ($this->id == 1) {
            return null;
        } else {
            return new Category($this->id - 1);
        }
    }

    public function getAncestors()
    {
        $parent = $this->parent;

        if (! $parent) {
            return [];
        }

        return array_merge($parent->ancestors, [$parent]);
    }
}
