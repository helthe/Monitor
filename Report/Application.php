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
 * Application details.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Application implements \JsonSerializable
{
    /**
     * The application context variables.
     *
     * @var array
     */
    private $context;

    /**
     * The application root directory.
     *
     * @var string
     */
    private $rootDirectory;

    /**
     * The application version.
     *
     * @var string
     */
    private $version;

    /**
     * Constructor.
     *
     * @param string $rootDirectory
     * @param string $version
     * @param array  $context
     */
    public function __construct($rootDirectory, $version = '', array $context = array())
    {
        $this->context = $context;
        $this->rootDirectory = $rootDirectory;
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'root_directory' => $this->rootDirectory,
            'version'        => $this->version,
            'context'        => $this->context
        );
    }
}
