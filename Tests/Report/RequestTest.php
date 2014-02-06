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

use Helthe\Monitor\Report\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $request = new Request('http://test.com/foo');
        $this->assertEquals('{"content":"","cookies":[],"headers":[],"method":"GET","post":[],"query":[],"url":"http:\/\/test.com\/foo"}', json_encode($request));

        $request = new Request('http://test.com/foo', 'POST', 'bar', array('foo1' => 'bar1'), array('foo2' => 'bar2'), array('foo3' => 'bar3'), array('foo4' => 'bar4'));
        $this->assertEquals('{"content":"bar","cookies":{"foo2":"bar2"},"headers":{"Foo1":"bar1"},"method":"POST","post":{"foo4":"bar4"},"query":{"foo3":"bar3"},"url":"http:\/\/test.com\/foo"}', json_encode($request));
    }
}
