<?php

namespace BreadcrumbsTests;

class FacadePhpDocTest extends TestCase
{
    private const TAG_REGEX = '/
            \*
            \s+
            @(?:param|return|throws|see)    # Tag
            \s+
            (.+?)                           # Value
            \s
            .*
        /x';

    private const ALLOWED_TYPE_REGEX = '/
        ^
        (
            # Fully qualified class
            \\\\.*
            # Self-referencing class
            |self(::.+)?
            # Built-in types
            |array|bool|callable|int|mixed|null|string|void
        )
        $
    /x';

    public function tagsInFacade()
    {
        $code = file_get_contents(__DIR__ . '/../src/Facades/Breadcrumbs.php');

        preg_match_all(static::TAG_REGEX, $code, $matches, PREG_SET_ORDER);

        $tags = [];
        foreach ($matches as $match) {
            foreach (explode('|', $match[1]) as $class) {
                // Return the whole line too so it can be seen in the error message
                yield [$class, $match[0]];
            }
        }
    }

    /** @dataProvider tagsInFacade */
    public function testFullyQualifiedClassNamesInFacade($class, $line)
    {
        // IDE Helper (v2.4.3) doesn't rewrite class names to FQCNs, so make sure only
        // fully qualified class names and built-in types are used in the Facade class
        $this->assertRegExp(
            static::ALLOWED_TYPE_REGEX,
            $class,
            "Must use fully qualified class names in Breadcrumbs Facade PhpDoc: $line"
        );
    }

    public function tagsInManager()
    {
        $code = file_get_contents(__DIR__ . '/../src/BreadcrumbsManager.php');

        preg_match_all(self::TAG_REGEX, $code, $matches, PREG_SET_ORDER);

        $tags = [];
        foreach ($matches as $match) {
            foreach (explode('|', $match[1]) as $class) {
                // Return the whole line too so it can be seen in the error message
                yield [$class, $match[0]];
            }
        }
    }

    /** @dataProvider tagsInManager */
    public function testFullyQualifiedClassNamesInManager($class, $line)
    {
        // IDE Helper (v2.4.3) doesn't rewrite class names to FQCNs, so make sure only
        // fully qualified class names and built-in types are used in the Manager class
        $this->assertRegExp(
            static::ALLOWED_TYPE_REGEX,
            $class,
            "Must use fully qualified class names in BreadcrumbsManger PhpDoc: $line"
        );
    }
}
