<?php

namespace Immutability\Tests;

use Immutability\ImmutableObject;

class ObjectTest extends TestCase
{
    public function testInit() {
        $testData = (object)[
            'test' => 5
        ];

        $o = new ImmutableObject($testData);

        $testData->test = 15;

        $this->assertEquals(5, $o->test);
    }

    /**
     * @expectedException Immutability\Exceptions\CannotModifyException
     */
    public function testSet() {
        $o1 = new ImmutableObject((object)[
            'test' => 5
        ]);

        $o1->test = 6;
    }

    public function testWith() {
        $o1 = new ImmutableObject((object)[
            'test' => 5
        ]);

        $o2 = $o1->with((object)[
            'test' => 10
        ]);

        $this->assertNotEquals($o1, $o2);
        $this->assertEquals(5, $o1->test);
        $this->assertEquals(10, $o2->test);
    }

    /**
     * @expectedException Immutability\Exceptions\CannotModifyException
     */
    public function testTwiceConstruct() {
        $o1 = new ImmutableObject((object)[
            'test' => 5
        ]);

        $o1->__construct((object)[
            'test' => 7
        ]);
    }

    /**
     * @expectedException Immutability\Exceptions\CannotModifyException
     */
    public function testUnset() {
        $o1 = new ImmutableObject((object)[
            'test' => 5
        ]);


        unset($o1->test);
    }

    public function testInitNestedObject() {
        $testData = (object)[
            'test' => (object)[
                'field' => 'test'
            ]
        ];

        $o = new ImmutableObject($testData);

        $testData->test->field = 'changed';

        $this->assertEquals('test', $o->test->field);
    }

    public function testArray() {
        $testData = (object)[
            'arr' => [
                (object)['test' => 1],
                (object)['test' => 2]
            ]
        ];

        $o = new ImmutableObject($testData);

        $testData->arr[0]->test = 5;

        $this->assertEquals(1, $o->arr[0]->test);
    }

    public function testAssocativeArray() {
        $testData = [
            'arr' => [
                'f1' => ['test' => 1],
                'f2' => ['test' => 2]
            ]
        ];

        $o = new ImmutableObject($testData);

        $testData['arr']['f1']['test'] = 5;

        $this->assertEquals(1, $o['arr']['f1']['test']);
    }

    public function testSetImmutable() {
        $testData = new ImmutableObject((object)[
            'test' => 5
        ]);

        $o = new ImmutableObject();
        $o->with($testData);

        $this->assertEquals(5, $o->test);
    }
}