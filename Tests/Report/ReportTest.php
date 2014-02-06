<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Report;

use Helthe\Monitor\Report\Application;
use Helthe\Monitor\Report\Report;
use Helthe\Monitor\Report\Request;
use Helthe\Monitor\Report\Server;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonEncode()
    {
        $report = new Report(new Application('foo/bar'), array(), new Request('http://foo.bar'), new Server('foo.bar'));

        $this->assertEquals('{"errors":[],"application":{"root_directory":"foo\/bar","version":"","context":[]},"request":{"content":"","cookies":[],"headers":[],"method":"GET","post":[],"query":[],"url":"http:\/\/foo.bar"},"server":{"name":"foo.bar","environment":null,"context":[]}}', json_encode($report));
    }
}
