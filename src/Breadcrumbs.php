<?php

namespace DaveJamesMiller\Breadcrumbs;

abstract class Breadcrumbs
{
    /**
     * @var Generator
     */
    protected $generator;


    /**
     * Breadcrumbs constructor.
     *
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }
}