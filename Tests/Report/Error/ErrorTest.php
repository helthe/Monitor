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

use Helthe\Monitor\Report\Error\Error;
use Helthe\Monitor\Report\Error\Frame;
use Helthe\Monitor\Report\Error\Trace;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithoutTrace()
    {
        $error = new Error('foo', E_RECOVERABLE_ERROR, 'Exception');

        $this->assertEquals('{"message":"foo","severity":4096,"trace":null,"type":"Exception"}', json_encode($error));
    }

    public function testConstructorWithTrace()
    {
        $trace = new Trace(array(new Frame('foo.php', 23)));
        $error = new Error('foo', E_RECOVERABLE_ERROR, 'Exception', $trace);

        $this->assertEquals('{"message":"foo","severity":4096,"trace":[{"file":"foo.php","line":23,"function":"","class":"","lines":[],"arguments":[]}],"type":"Exception"}', json_encode($error));
    }
}
