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

use Helthe\Monitor\Trace\ShiftArgumentsPass;

class ShiftArgumentsPassTest extends \PHPUnit_Framework_TestCase
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
        $this->pass = new ShiftArgumentsPass();
    }

    public function testEmptyArray()
    {
        $trace = array();

        $trace = $this->pass->transform($trace);

        $this->assertCount(0, $trace);
    }

    public function testOneLevelWithArgs()
    {
        $expected = array(
            array(
            )
        );
        $trace = array(
            array(
                'args' => array('bar.php'),
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testOneLevelWithArguments()
    {
        $expected = array(
            array(
            )
        );
        $trace = array(
            array(
                'arguments' => array('bar.php'),
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testMultipleLevelsWithArgs()
    {
        $expected = array(
            array(
                'file' => 'foo.php',
                'line' => 13,
                'args' => array('foo.php'),
            ),
            array(
                'file' => 'bar.php',
                'line' => 26,
            )
        );
        $trace = array(
            array(
                'file' => 'foo.php',
                'line' => 13,
                'args' => array('bar.php'),
            ),
            array(
                'file' => 'bar.php',
                'line' => 26,
                'args' => array('foo.php'),
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }

    public function testMultipleLevelsWithArguments()
    {
        $expected = array(
            array(
                'file'      => 'foo.php',
                'line'      => 13,
                'arguments' => array('foo.php'),
            ),
            array(
                'file' => 'bar.php',
                'line' => 26,
            )
        );
        $trace = array(
            array(
                'file'      => 'foo.php',
                'line'      => 13,
                'arguments' => array('bar.php'),
            ),
            array(
                'file'      => 'bar.php',
                'line'      => 26,
                'arguments' => array('foo.php'),
            )
        );

        $trace = $this->pass->transform($trace);

        $this->assertEquals($expected, $trace);
    }
}
