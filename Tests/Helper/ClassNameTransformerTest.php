<?php

namespace Tests\Becklyn\RadBundle\Helper;

use Becklyn\RadBundle\Helper\ClassNameTransformer;
use PHPUnit\Framework\TestCase;


/**
 *
 */
class ClassNameTransformerTest extends TestCase
{
    public function getData ()
    {
        return [
            ["TestBundle\\Model\\TestModel", "TestBundle\\Entity\\Test", "bundle: simple case"],
            ["ABC\\TestBundle\\Model\\TestModel", "ABC\\TestBundle\\Entity\\Test", "bundle: correct with nesting"],
            ["TestBundle\\Model\\Sub\\TestModel", "TestBundle\\Entity\\Sub\\Test", "bundle: support sub-namespace"],
            ["TestBundle\\Model\\Sub\\Sub2\\TestModel", "TestBundle\\Entity\\Sub\\Sub2\\Test", "bundle: support 2x sub-namespace"],

            ["App\\Model\\TestModel", "App\\Entity\\Test", "bundle-less: simple case"],
            ["App\\Model\\Sub\\Sub2\\TestModel", "App\\Entity\\Sub\\Sub2\\Test", "bundle-less: correct with nesting"],
            ["App\\Model\\Sub\\TestModel", "App\\Entity\\Sub\\Test", "bundle-less: support sub-namespace"],

            ["TestBundle\\Model\\Test", "TestBundle\\Model\\Test", "invalid: no model"],
            ["TestBundle\\Model\\Model", "TestBundle\\Model\\Model", "invalid edge case: empty entity name"],
        ];
    }



    /**
     * @dataProvider getData
     *
     * @param string $modelClassName
     * @param string $expectedEntityClassName
     * @param string $message
     */
    public function testValue ($modelClassName, $expectedEntityClassName, $message)
    {
        $transformer = new ClassNameTransformer();
        $actual = $transformer->transformModelToEntity($modelClassName);
        return $this->assertEquals($expectedEntityClassName, $actual, $message);
    }
}
