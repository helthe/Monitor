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

use Helthe\Monitor\Report\Error\Frame;
use Helthe\Monitor\Report\Error\Trace;

class TraceTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyTrace()
    {
        $trace = new Trace(array());

        $this->assertEquals('[]', json_encode($trace));
    }

    public function testNonEmptyTrace()
    {
        $trace = new Trace(array(
            new Frame('foo.php', 13)
        ));

        $this->assertEquals('[{"file":"foo.php","line":13,"function":"","class":"","lines":[],"arguments":[]}]', json_encode($trace));
    }
}
