<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Report\Error;

use Helthe\Monitor\Report\Error\Argument;

class ArgumentTest extends \PHPUnit_Framework_TestCase
{
    public function testNull()
    {
        $argument = new Argument(null);

        $this->assertEquals('null', json_encode($argument));
    }

    public function testEmptyString()
    {
        $argument = new Argument('');

        $this->assertEquals('""', json_encode($argument));
    }

    public function testString()
    {
        $argument = new Argument('test');

        $this->assertEquals('"test"', json_encode($argument));
    }

    public function testInteger()
    {
        $argument = new Argument(42);

        $this->assertEquals('42', json_encode($argument));
    }

    public function testBooleanFalse()
    {
        $argument = new Argument(false);

        $this->assertEquals('false', json_encode($argument));
    }

    public function testBooleanTrue()
    {
        $argument = new Argument(true);

        $this->assertEquals('true', json_encode($argument));
    }

    public function testEmptyArray()
    {
        $argument = new Argument(array());

        $this->assertEquals('[]', json_encode($argument));
    }

    public function testArray()
    {
        $argument = new Argument(array('foo', 'bar'));

        $this->assertEquals('["foo","bar"]', json_encode($argument));
    }

    public function testAssociativeArray()
    {
        $argument = new Argument(array('foo' => 'bar'));

        $this->assertEquals('{"foo":"bar"}', json_encode($argument));
    }

    public function testStdObject()
    {
        $object = new \stdClass();
        $object->foo = 'bar';
        $argument = new Argument($object);

        $this->assertEquals('{"foo":"bar"}', json_encode($argument));
    }

    public function testEmptyEncodedObject()
    {
        $object = new \stdClass();
        $argument = new Argument($object);

        $this->assertEquals('"Object stdClass"', json_encode($argument));
    }
}
