<?php
use DaveJamesMiller\Breadcrumbs\Generator;
use Mockery as m;

class GeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $generator = new Generator(array());
        $breadcrumbs = array(123);

        // Note: This is not recommended - I can't actually remember why I
        // included a set method like this. But for BC I won't delete it.
        $generator->set($breadcrumbs);

        $this->assertSame($breadcrumbs, $generator->get());
    }

    public function testParent()
    {
        // Can't find a simple way to test that a closure is called, so make a
        // mock object instead and pass an array as the callback
        $mock = m::mock();

        $callbacks = array(
            'sample' => array($mock, 'callback'),
        );

        $generator = new Generator($callbacks);

        $mock->shouldReceive('callback')
            ->with($generator, 1, 2)
            ->times(3);

        $generator->parent('sample', 1, 2);
        $generator->parentArray('sample', array(1, 2));
        $generator->call('sample', array(1, 2));
    }

    public function testPush()
    {
        $generator = new Generator(array());
        $generator->push('Home', '/');
        $breadcrumbs = $generator->get();

        $this->assertCount(1, $breadcrumbs);

        $this->assertSame('Home', $breadcrumbs[0]->title);
        $this->assertSame('/', $breadcrumbs[0]->url);
    }

    public function testPushWithoutUrl()
    {
        $generator = new Generator(array());
        $generator->push('Home');
        $breadcrumbs = $generator->get();

        $this->assertCount(1, $breadcrumbs);

        $this->assertSame('Home', $breadcrumbs[0]->title);
        $this->assertNull($breadcrumbs[0]->url);
    }

    public function testToArray()
    {
        $generator = new Generator(array());
        $generator->push('Home', '/');
        $generator->push('Home', '/');
        $generator->push('Home', '/');
        $breadcrumbs = $generator->toArray();

        $this->assertCount(3, $breadcrumbs);

        $this->assertTrue($breadcrumbs[0]->first);
        $this->assertFalse($breadcrumbs[1]->first);
        $this->assertFalse($breadcrumbs[2]->first);

        $this->assertFalse($breadcrumbs[0]->last);
        $this->assertFalse($breadcrumbs[1]->last);
        $this->assertTrue($breadcrumbs[2]->last);
    }
}
