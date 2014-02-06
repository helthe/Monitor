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
use Helthe\Monitor\Report\Error\Frame;

class FrameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructorProvider
     */
    public function testConstructor($file, $line, $function, $class, $arguments, $expected)
    {
        $frame = new Frame($file, $line, $function, $class, $arguments);

        $this->assertEquals($expected, json_encode($frame));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConstructor()
    {
        $frame = new Frame('foo.php', 11, '', 'Foo');
    }

    public function testGetSourceCodeLines()
    {
        $frame = new Frame(__FILE__, 5);

        $this->assertRegExp('%"lines":{"2":"","3":"\\\\/\*","4":" \* This file is part of the Helthe Monitor package\.","5":" \*","6":" \* \(c\) Carl Alexander <carlalexander@helthe.co>","7":" \*","8":" \* For the full copyright and license information, please view the LICENSE"}%', json_encode($frame));
    }

    public function testGetSourceCodeLinesWithStartOfFile()
    {
        $frame = new Frame(__FILE__, 2);

        $this->assertRegExp('%"lines":{"1":"<\?php","2":"","3":"\\\\/\*","4":" \* This file is part of the Helthe Monitor package\.","5":" \*"}%', json_encode($frame));
    }

    public function constructorProvider()
    {
        return array(
            array('foo.php', 11, '', '', array(), '{"file":"foo.php","line":11,"function":"","class":"","lines":[],"arguments":[]}'),
            array('foo.php', 11, 'test', '', array(), '{"file":"foo.php","line":11,"function":"test","class":"","lines":[],"arguments":[]}'),
            array('foo.php', 11, 'test', 'Foo', array(), '{"file":"foo.php","line":11,"function":"test","class":"Foo","lines":[],"arguments":[]}'),
            array('foo.php', 11, '', '', array('file' => new Argument('foo.bar')), '{"file":"foo.php","line":11,"function":"","class":"","lines":[],"arguments":{"file":"foo.bar"}}'),
            array('foo.php', 11, '', '', array('foo' => new Argument('bar'), 'bar' => new Argument('foo')), '{"file":"foo.php","line":11,"function":"","class":"","lines":[],"arguments":{"foo":"bar","bar":"foo"}}')
        );
    }
}
