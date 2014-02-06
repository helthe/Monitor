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

use Helthe\Monitor\Trace\SanitizeFramesPass;

class SanitizeFramesPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SanitizeFramesPass
     */
    private $pass;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->pass = new SanitizeFramesPass();
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
            array(
                'file'      => 'foo.php',
                'line'      => 13,
                'function'  => 'bar',
                'class'     => 'Foo',
                'arguments' => array('bar.php'),
            )
        );
        $trace = array(
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'bar',
                'class'    => 'Foo',
                'args'     => array('bar.php'),
                'type'     => '::'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testMixedArray()
    {
        $expected = array(
            array(
                'file'      => 'foo.php',
                'line'      => 13,
                'function'  => 'bar',
                'class'     => 'Foo',
                'arguments' => array('bar.php'),
            )
        );
        $trace = array(
            array('foo' => 'bar'),
            array(
                'file'     => 'foo.php',
                'line'     => 13,
                'function' => 'bar',
                'class'    => 'Foo',
                'args'     => array('bar.php'),
                'type'     => '::'
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }
}
