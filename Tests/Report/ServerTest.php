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

use Helthe\Monitor\Report\Server;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $server = new Server('foo');

        $this->assertEquals('{"name":"foo","environment":null,"context":[]}', json_encode($server));
    }

    public function testCreateFromGlobals()
    {
        $server = Server::createFromGlobals();

        $this->assertRegExp('/"name":"'. php_uname('n') . '"/', json_encode($server));
    }
}
