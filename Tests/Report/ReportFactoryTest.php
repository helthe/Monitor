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
use Helthe\Monitor\Report\Error\Error;
use Helthe\Monitor\Report\Report;
use Helthe\Monitor\Report\ReportFactory;
use Helthe\Monitor\Report\Request;
use Helthe\Monitor\Report\Server;

class ReportFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromErrors()
    {
        $application = new Application('foo\bar');
        $request = new Request('http://foo.bar');
        $server = new Server('foo.bar');
        $errors = array(new Error('foo', E_RECOVERABLE_ERROR, 'Exception'));

        $factory = new ReportFactory($application, $request, $server);
        $report = $factory->createFromErrors($errors);

        $this->assertEquals(new Report($application, $errors, $request, $server), $report);
    }
}
