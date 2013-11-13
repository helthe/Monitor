<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Exception;

/**
 * Error Exception with variable context.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
Class ContextErrorException extends \ErrorException
{
    /**
     * @var array
     */
    private $context;

    /**
     * Constructor.
     *
     * @param string $message
     * @param int    $code
     * @param int    $severity
     * @param string $filename
     * @param int    $lineno
     * @param array  $context
     */
    public function __construct($message, $code, $severity, $filename, $lineno, $context = array())
    {
        parent::__construct($message, $code, $severity, $filename, $lineno);

        $this->context = $context;
    }

    /**
     * Get the variable context.
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
