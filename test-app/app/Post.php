<?php namespace App;

class Post
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
        return 'Post ' . $this->id;
    }

    public function getCategory()
    {
        return new Category($this->id);
    }

    public function getImage()
    {
        return "images/post{$this->id}.jpg";
    }
}
