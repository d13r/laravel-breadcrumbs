<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Route;
use URL;

class ReadmeTest extends TestCase
{
    // This is just to make sure I know if any of the Laravel functionality mentioned in the README changes

    public function testRouting()
    {
        Route::name('routename')->get('route/{param1?}/{param2?}', 'controller@action');

        $this->assertSame('http://localhost/path/to/route', url('path/to/route'), "url('path/to/route')");
        $this->assertSame('http://localhost/path/to/route', URL::to('path/to/route'), "URL::to('path/to/route')");
        $this->assertSame('https://localhost/path/to/route', secure_url('path/to/route'), "secure_url('path/to/route')");
        $this->assertSame('http://localhost/route', route('routename'), "route('routename')");
        $this->assertSame('http://localhost/route/param', route('routename', 'param'), "route('routename', 'param')");
        $this->assertSame('http://localhost/route/param1/param2', route('routename', ['param1', 'param2']), "route('routename', ['param1', 'param2'])");
        $this->assertSame('http://localhost/route', URL::route('routename'), "URL::route('routename')");
        $this->assertSame('http://localhost/route/param', URL::route('routename', 'param'), "URL::route('routename', 'param')");
        $this->assertSame('http://localhost/route/param1/param2', URL::route('routename', ['param1', 'param2']), "URL::route('routename', ['param1', 'param2'])");
        $this->assertSame('http://localhost/route', action('controller@action'), "action('controller@action')");
        $this->assertSame('http://localhost/route', URL::action('controller@action'), "URL::action('controller@action')");
    }
}
