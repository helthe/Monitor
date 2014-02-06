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
 * A PHP error.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Error implements \JsonSerializable
{
    /**
     * Error message.
     *
     * @var string
     */
    private $message;

    /**
     * Severity level.
     *
     * @var integer
     */
    private $severity;

    /**
     * Stacktrace
     *
     * @var Trace
     */
    private $trace;

    /**
     * Exception type
     *
     * @var string
     */
    private $type;

    /**
     * Constructor.
     *
     * @param string  $message
     * @param integer $severity
     * @param string  $type
     * @param Trace   $trace
     */
    public function __construct($message, $severity, $type, Trace $trace = null)
    {
        $this->message = $message;
        $this->severity = $severity;
        $this->type = $type;
        $this->trace = $trace;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'message'  => $this->message,
            'severity' => $this->severity,
            'trace'    => $this->trace,
            'type'     => $this->type
        );
    }
}
