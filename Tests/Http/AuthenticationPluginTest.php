<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Http;

use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestFactory;
use Helthe\Monitor\Http\AuthenticationPlugin;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AuthenticationPluginTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscribesToEvents()
    {
        $events = AuthenticationPlugin::getSubscribedEvents();
        $this->assertArrayHasKey('request.before_send', $events);
    }

    public function testAddsHeader()
    {
        $plugin = new AuthenticationPlugin('foo');
        $dispatcher = new EventDispatcher();

        $dispatcher->addSubscriber($plugin);

        $request = RequestFactory::getInstance()->create('POST', 'http://www.example.com');
        $event = new Event(array(
            'request' => $request
        ));
        $dispatcher->dispatch('request.before_send', $event);
        $headers = $event['request']->getHeaders();

        $this->assertArrayHasKey('helthe-api-key', $headers);
        $this->assertEquals('foo', $headers['helthe-api-key']);
    }
}
