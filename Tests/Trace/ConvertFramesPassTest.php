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
use Helthe\Monitor\Report\Error\Frame;
use Helthe\Monitor\Trace\ConvertFramesPass;

class ConvertFramesPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConvertFramesPass
     */
    private $pass;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->pass = new ConvertFramesPass();
    }

    public function testEmptyArray()
    {
        $trace = array();

        $trace = $this->pass->transform($trace);

        $this->assertCount(0, $trace);
    }

    public function testInvalidArray()
    {
        $trace = array(
            array('foo' => 'bar')
        );

        $trace = $this->pass->transform($trace);

        $this->assertCount(0, $trace);
    }

    public function testMissingFile()
    {
        $trace = array(
            array('line' => 13)
        );

        $trace = $this->pass->transform($trace);

        $this->assertCount(0, $trace);
    }

    public function testMissingLine()
    {
        $trace = array(
            array('file' => 'foo.php')
        );

        $trace = $this->pass->transform($trace);

        $this->assertCount(0, $trace);
    }

    public function testValidArray()
    {
        $expected = array(
            new Frame('foo.php', 13, 'bar', 'Foo')
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'bar',
                'class'    => 'Foo'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testValidArrayWithArgs()
    {
        $expected = array(
            new Frame('foo.php', 13, 'bar', 'Foo', array('str' => new Argument('bar')))
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'bar',
                'class'    => 'Foo',
                'args'     => array(
                    'str' => new Argument('bar'),
                ),
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testValidArrayWithArguments()
    {
        $expected = array(
            new Frame('foo.php', 13, 'bar', 'Foo', array('str' => new Argument('bar')))
        );
        $trace = array(
            array(
                'file'      => 'foo.php',
                'line'      => 13,
                'function'  => 'bar',
                'class'     => 'Foo',
                'arguments' => array(
                    'str' => new Argument('bar'),
                ),
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }
}
