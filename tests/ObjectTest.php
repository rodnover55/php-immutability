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

        $this->assertInstanceOf(ImmutableObject::class, $this->test);
    }
}