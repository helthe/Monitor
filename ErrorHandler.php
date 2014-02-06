<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor;

use Helthe\Monitor\Exception\FatalErrorException;
use Helthe\Monitor\Report\Error\Error;
use Helthe\Monitor\Report\Error\ErrorFactory;

/**
 * Error handler for Helthe Monitor.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ErrorHandler
{
    /**
     * All handled errors.
     *
     * @var Error[]
     */
    private $errors;

    /**
     * Error factory.
     *
     * @var ErrorFactory
     */
    private $factory;

    /**
     * @var string
     */
    private $reservedMemory;

    /**
     * Registers the error handler.
     *
     * @param ErrorFactory $factory
     * @param integer      $level
     *
     * @return ErrorHandler
     */
    public static function register(ErrorFactory $factory, $level = null)
    {
        $handler = new self($factory);

        if (null !== $level) {
            error_reporting($level);
        }

        set_error_handler(array($handler, 'handleError'), $level);
        set_exception_handler(array($handler, 'handleException'));
        register_shutdown_function(array($handler, 'handleFatal'));

        return $handler;
    }

    /**
     * Constructor.
     *
     * @param ErrorFactory $factory
     */
    public function __construct(ErrorFactory $factory)
    {
        $this->factory = $factory;
        $this->errors = array();
        $this->reservedMemory = str_repeat('x', 10240);
    }

    /**
     * Get all handled errors.
     *
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Handles errors.
     *
     * @param integer $level
     * @param string  $message
     * @param string  $file
     * @param integer $line
     * @param array   $context
     *
     * @return boolean
     */
    public function handleError($level, $message, $file = 'unknown', $line = 0, array $context = array())
    {
        $this->handleException(new \ErrorException($message, 0, $level, $file, $line));

        return false;
    }

    /**
     * Handles an exception.
     *
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        $this->errors[] = $this->factory->createError($exception);
    }

    /**
     * Handles fatal errors.
     */
    public function handleFatal()
    {
        if (null === $error = error_get_last()) {
            return;
        }

        unset($this->reservedMemory);
        $level = $error['type'];

        // Only handle PHP fatal errors
        if (!in_array($level, array(E_ERROR, E_USER_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE))) {
            return;
        }

        $this->handleException(new FatalErrorException($error['message'], 0, $level, $error['file'], $error['line']));
    }
}
