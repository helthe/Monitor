<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Report;

/**
 * Server details.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Server implements \JsonSerializable
{
    /**
     * Context variables for the server information.
     *
     * @var array
     */
    private $context;

    /**
     * Environment name.
     *
     * @var string
     */
    private $environment;

    /**
     * Host name of the server.
     *
     * @var string
     */
    private $name;

    /**
     * Creates an instance of Server from PHP globals.
     *
     * @return Server
     */
    public static function createFromGlobals()
    {
        return new self(php_uname('n'), null, $_SERVER);
    }

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $environment
     * @param array  $context
     */
    public function __construct($name, $environment = null, array $context = array())
    {
        $this->name = $name;
        $this->environment = $environment;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'name'        => $this->name,
            'environment' => $this->environment,
            'context'     => $this->context
        );
    }
}
