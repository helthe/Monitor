<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Plugin;

use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The AuthenticationPlugin modifies a request so that it can properly be authenticated with Helthe.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
final class AuthenticationPlugin implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * Constructor.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => 'onRequestBeforeSend',
        );
    }

    /**
     * Add the api key to the headers before a request is sent.
     *
     * @param Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        $event['request']->setHeader('helthe-api-key', $this->apiKey);
    }
}