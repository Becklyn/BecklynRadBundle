<?php

namespace Becklyn\RadBundle\Tests\Helper;

use Becklyn\RadBundle\Helper\ClassNameTransformer;


/**
 *
 */
class ClassNameTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function getData ()
    {
        return [
            ["TestBundle\\Model\\TestModel", "TestBundle\\Entity\\Test", "simple case"],
            ["ABC\\TestBundle\\Model\\TestModel", "ABC\\TestBundle\\Entity\\Test", "correct with nesting"],
            ["TestBundle\\Model\\Sub\\TestModel", "TestBundle\\Entity\\Sub\\Test", "support sub-namespace"],
            ["TestBundle\\Model\\Sub\\Sub2\\TestModel", "TestBundle\\Entity\\Sub\\Sub2\\Test", "support 2x sub-namespace"],
            ["Test\\Model\\TestModel", "Test\\Model\\TestModel", "invalid: no bundle"],
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
