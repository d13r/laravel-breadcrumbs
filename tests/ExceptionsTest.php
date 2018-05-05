<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Config;
use Route;
use URL;

class ExceptionsTest extends TestCase
{
    // Also see RouteBoundTest which tests the route binding-related exceptions

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\DuplicateBreadcrumbException
     * @expectedExceptionMessage Breadcrumb name "duplicate" has already been registered
     */
    public function testDuplicateBreadcrumbException()
    {
        Breadcrumbs::for('duplicate', function () { });
        Breadcrumbs::for('duplicate', function () { });
    }

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException
     * @expectedExceptionMessage Breadcrumb not found with name "invalid"
     */
    public function testInvalidBreadcrumbException()
    {
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

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\ViewNotSetException
     * @expectedExceptionMessage Breadcrumbs view not specified (check config/breadcrumbs.php)
     */
    public function testViewNotSetException()
    {
        Config::set('breadcrumbs.view', '');

        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', url('/'));
        });

        Breadcrumbs::render('home');
    }
}
