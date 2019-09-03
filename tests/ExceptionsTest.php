<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Config;

class ExceptionsTest extends TestCase
{
    // Also see RouteBoundTest which tests the route binding-related exceptions
    // and IgnitionTest which tests the Laravel Ignition integration (solutions)

    public function testDuplicateBreadcrumbException()
    {
        $this->expectException(\DaveJamesMiller\Breadcrumbs\Exceptions\DuplicateBreadcrumbException::class);
        $this->expectExceptionMessage('Breadcrumb name "duplicate" has already been registered');

        Breadcrumbs::for('duplicate', function () { });
        Breadcrumbs::for('duplicate', function () { });
    }

    public function testInvalidBreadcrumbException()
    {
        $this->expectException(\DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException::class);
        $this->expectExceptionMessage('Breadcrumb not found with name "invalid"');

        Breadcrumbs::render('invalid');
    }

    public function testInvalidBreadcrumbExceptionDisabled()
    {
        Config::set('breadcrumbs.invalid-named-breadcrumb-exception', false);

        $html = Breadcrumbs::render('invalid')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <p>No breadcrumbs</p>
        ', $html);
    }

    public function testViewNotSetException()
    {
        $this->expectException(\DaveJamesMiller\Breadcrumbs\Exceptions\ViewNotSetException::class);
        $this->expectExceptionMessage('Breadcrumbs view not specified (check config/breadcrumbs.php)');

        Config::set('breadcrumbs.view', '');

        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', url('/'));
        });

        Breadcrumbs::render('home');
    }
}
