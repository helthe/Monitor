<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Report\Error;

/**
 * A function or method argument.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Argument implements \JsonSerializable
{
    /**
     * The argument.
     *
     * @var mixed
     */
    private $argument;

    /**
     * Constructor.
     *
     * @param mixed $argument
     */
    public function __construct($argument)
    {
        $this->argument = $argument;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $argument = $this->argument;
        $encoded = json_encode($argument);

        if ('{}' != $encoded) {
            return $argument;
        }

        if (is_object($argument)) {
            $argument = 'Object ' . get_class($argument);
        }

        return $argument;
    }
}
