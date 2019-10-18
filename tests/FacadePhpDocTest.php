<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsManager;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class FacadePhpDocTest extends TestCase
{
    /**
     * Methods that are not needed in phpDoc
     */
    private const EXCLUSION_METHODS = [
        '__construct',
        '__destruct',
        '__call',
        '__callStatic',
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__sleep',
        '__wakeup',
        '__toString',
        '__invoke',
        '__set_state',
        '__clone',
        '__debugInfo',
    ];

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

    public function testBreadcrumbsFacade()
    {
        $this->checkMethodDocBlock(Breadcrumbs::class, BreadcrumbsManager::class);
    }

    /**
     * Checks the correctness of building the doc block according to the main class
     *
     * @param string $facade
     * @param string $class
     *
     * @throws \ReflectionException
     */
    private function checkMethodDocBlock(string $facade, string $class)
    {
        $class = new ReflectionClass($class);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $facadeDocs = (new ReflectionClass($facade))->getDocComment();

        collect($methods)
            ->map(function (ReflectionMethod $method) {
                return in_array($method->name, self::EXCLUSION_METHODS, true) ? null : $method;
            })
            ->filter()
            ->map(function (ReflectionMethod $method) {

                $parameters = $this->buildParametsDocBlock($method->getParameters());
                $returns = $this->buildReturnTypeDocBlock($method->getReturnType());

                $docMethod = sprintf('@method static %s %s%s',
                    $returns,
                    $method->getName(),
                    $parameters
                );

                return preg_replace('/\s+/', ' ', $docMethod);
            })
            ->each(function (string $method) use ($facadeDocs) {
                $this->assertStringContainsString($method, $facadeDocs, 'Not found: ' . $method);
            });
    }

    /**
     * @param ReflectionParameter[] $parameters
     *
     * @return string
     */
    private function buildParametsDocBlock($parameters = [])
    {
        $parameters = array_map(function (ReflectionParameter $parameter) {
            $name = optional($parameter->getType())->getName();

            $strParam = sprintf('%s $%s', $name, $parameter->getName());
            $strParam = trim($strParam);

            if (!$parameter->isDefaultValueAvailable()) {
                return $strParam;
            }

            $defaultValue = $parameter->getDefaultValue() ?? 'null';
            return sprintf('%s = %s', $strParam, $defaultValue);
        }, $parameters);

        return sprintf('(%s)', implode(', ', $parameters));
    }

    /**
     * @param \ReflectionType|null $reflectionType
     *
     * @return string
     */
    private function buildReturnTypeDocBlock(\ReflectionType $reflectionType = null)
    {
        $reflectionType = optional($reflectionType);

        $strReturn = $reflectionType->getName();

        if (class_exists($strReturn)) {
            $strReturn = Str::start($strReturn, '\\');
        }

        if ($reflectionType->allowsNull()) {
            $strReturn = sprintf('%s|%s', $strReturn, 'null');
        }

        return $strReturn;
    }
}
