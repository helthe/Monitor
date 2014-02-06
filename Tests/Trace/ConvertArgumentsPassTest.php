<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Trace;

use Helthe\Monitor\Report\Error\Argument;
use Helthe\Monitor\Trace\ConvertArgumentsPass;

class ConvertArgumentsPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConvertArgumentsPass
     */
    private $pass;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->pass = new ConvertArgumentsPass();
    }

    public function testEmptyArray()
    {
        $trace = array();

        $trace = $this->pass->transform($trace);

        $this->assertCount(0, $trace);
    }

    public function testInvalidArray()
    {
        $expected = array(
            array('foo' => 'bar')
        );
        $trace = array(
            array('foo' => 'bar')
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testIncludeFunction()
    {
        $expected = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'include',
                'class'    => 'Foo',
                'args'     => array(
                    'file' => new Argument('bar.php')
                ),
                'type'     => '::'
            )
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'include',
                'class'    => 'Foo',
                'args'     => array('bar.php'),
                'type'     => '::'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testExistingMethod()
    {
        $expected = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'bar',
                'class'    => 'Helthe\Monitor\Tests\Fixture\Foo',
                'args'     => array(
                    'foo' => new Argument('foo'),
                    'bar' => new Argument('bar'),
                ),
                'type'     => '::'
            )
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'bar',
                'class'    => 'Helthe\Monitor\Tests\Fixture\Foo',
                'args'     => array('foo'),
                'type'     => '::'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testCallStaticMethod()
    {
        $expected = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'foo',
                'class'    => 'Helthe\Monitor\Tests\Fixture\Foo',
                'args'     => array(
                    'name'      => new Argument('foo'),
                    'arguments' => new Argument('bar'),
                ),
                'type'     => '::'
            )
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'foo',
                'class'    => 'Helthe\Monitor\Tests\Fixture\Foo',
                'args'     => array('foo', 'bar'),
                'type'     => '::'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testCallMethod()
    {
        $expected = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'foo',
                'class'    => 'Helthe\Monitor\Tests\Fixture\Foo',
                'args'     => array(
                    'name'      => new Argument('foo'),
                    'arguments' => new Argument('bar'),
                ),
                'type'     => '->'
            )
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'foo',
                'class'    => 'Helthe\Monitor\Tests\Fixture\Foo',
                'args'     => array('foo', 'bar'),
                'type'     => '->'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testFunction()
    {
        $expected = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'strtolower',
                'args'     => array(
                    'str' => new Argument('bar'),
                ),
                'type'     => '::'
            )
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'strtolower',
                'args'     => array('bar'),
                'type'     => '::'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }
}
