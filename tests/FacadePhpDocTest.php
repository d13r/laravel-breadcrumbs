<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsManager;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionType;

class FacadePhpDocTest extends TestCase
{
    public function tags()
    {
        $code = file_get_contents(__DIR__ . '/../src/BreadcrumbsManager.php');

        $pattern = '/
            \*
            \s+
            @(?:param|return|throws)
            \s+
            (.+?)
            \s
            .*
        /x';

        preg_match_all($pattern, $code, $matches, PREG_SET_ORDER);

        $tags = [];
        foreach ($matches as $match) {
            foreach (explode('|', $match[1]) as $class) {
                // Return the whole line too so it can be seen in the error message
                yield [$class, $match[0]];
            }
        }
    }

    /** @dataProvider tags */
    public function testFullyQualifiedClassNames($class, $line)
    {
        // IDE Helper (v2.4.3) doesn't rewrite class names to FQCNs, so make sure only
        // fully qualified class names and built-in types are used in the Manager class
        $this->assertRegExp(
            '/^(\\\\.*|array|bool|callable|int|mixed|null|string|void)$/',
            $class,
            "Must use fully qualified class names in BreadcrumbsManger PhpDoc: $line"
        );
    }

    public function dataProviderManagerMethods()
    {

    }

    public function testBreadcrumbsFacade()
    {
        $class = new ReflectionClass(BreadcrumbsManager::class);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $facadeDocs = (new ReflectionClass(Breadcrumbs::class))->getDocComment();

        collect($methods)
            ->filter(function (ReflectionMethod $method) {
                return !Str::startsWith($method->name, '__');
            })
            ->map(function (ReflectionMethod $method) {

                $docMethod = sprintf('* @method static %s %s(%s)',
                    $this->buildReturnTypeDocBlock($method->getReturnType()),
                    $method->getName(),
                    $this->buildParametersDocBlock($method->getParameters())
                );

                return preg_replace('/\s+/', ' ', $docMethod);
            })
            ->each(function (string $method) use ($facadeDocs) {
                $this->assertStringContainsString($method, $facadeDocs, 'Invalid docblock on Breadcrumbs facade');
            });
    }

    private function buildParametersDocBlock($parameters = []): string
    {
        return collect($parameters)
            ->map(static function (ReflectionParameter $parameter) {
                $doc = '$' . $parameter->getName();

                if ($parameter->isVariadic()) {
                    $doc = '...' . $doc;
                }

                if ($type = $parameter->getType()) {
                    $doc = $type->getName() . ' ' . $doc;
                }

                if ($parameter->isDefaultValueAvailable()) {
                    $doc .= ' = ' . var_export($parameter->getDefaultValue(), true);
                }

                return $doc;
            })
            ->implode(', ');
    }

    private function buildReturnTypeDocBlock(ReflectionType $reflectionType = null): ?string
    {
        if (!$reflectionType) {
            return '';
        }

        $doc = $reflectionType->getName();

        if (!$reflectionType->isBuiltin()) {
            $doc = Str::start($doc, '\\');
        }

        if ($reflectionType->allowsNull()) {
            $doc .= '|null';
        }

        return $doc;
    }
}
