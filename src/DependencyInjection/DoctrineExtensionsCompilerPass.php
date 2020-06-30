<?php declare(strict_types=1);

namespace Becklyn\RadBundle\DependencyInjection;

use DoctrineExtensions\Query\Mysql\CharLength;
use DoctrineExtensions\Query\Mysql\DateFormat;
use DoctrineExtensions\Query\Mysql\IfElse;
use DoctrineExtensions\Query\Mysql\IfNull;
use DoctrineExtensions\Query\Mysql\Lpad;
use DoctrineExtensions\Query\Mysql\Rand;
use DoctrineExtensions\Query\Mysql\Week;
use DoctrineExtensions\Query\Mysql\Year;
use Scienta\DoctrineJsonFunctions\Query\AST\Functions\Mysql\JsonExtract;
use Scienta\DoctrineJsonFunctions\Query\AST\Functions\Mysql\JsonSearch;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineExtensionsCompilerPass implements CompilerPassInterface
{
    /**
     */
    public function process (ContainerBuilder $container) : void
    {
        foreach ($container->getDefinitions() as $name => $definition)
        {
            if (\preg_match("~^doctrine\\.orm\\..+_configuration$~", $name))
            {
                $definition
                    // string functions
                    ->addMethodCall("addCustomStringFunction", ["CHAR_LENGTH", CharLength::class])
                    ->addMethodCall("addCustomStringFunction", ["DATE_FORMAT", DateFormat::class])
                    ->addMethodCall("addCustomStringFunction", ["IFELSE", IfElse::class])
                    ->addMethodCall("addCustomStringFunction", ["IFNULL", IfNull::class])
                    ->addMethodCall("addCustomStringFunction", ["JSON_EXTRACT", JsonExtract::class])
                    ->addMethodCall("addCustomStringFunction", ["JSON_SEARCH", JsonSearch::class])
                    ->addMethodCall("addCustomStringFunction", ["LPAD", Lpad::class])

                    // numeric functions
                    ->addMethodCall("addCustomNumericFunction", ["RAND", Rand::class])

                    // datetime functions
                    ->addMethodCall("addCustomDatetimeFunction", ["WEEK", Week::class])
                    ->addMethodCall("addCustomDatetimeFunction", ["YEAR", Year::class]);
            }
        }
    }
}
