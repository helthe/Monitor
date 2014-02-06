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

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testWithOnlyRootDirectory()
    {
        $application = new Application('foo/bar');

        $this->assertEquals('{"root_directory":"foo\/bar","version":"","context":[]}', json_encode($application));
    }

    public function testWithNoContext()
    {
        $application = new Application('foo/bar', 'test');

        $this->assertEquals('{"root_directory":"foo\/bar","version":"test","context":[]}', json_encode($application));
    }

    public function testWithNoVersion()
    {
        $application = new Application('foo/bar', '', array('_route' => 'test'));

        $this->assertEquals('{"root_directory":"foo\/bar","version":"","context":{"_route":"test"}}', json_encode($application));
    }

    public function testWithEverything()
    {
        $application = new Application('foo/bar', 'test', array('_route' => 'test'));

        $this->assertEquals('{"root_directory":"foo\/bar","version":"test","context":{"_route":"test"}}', json_encode($application));
    }
}
