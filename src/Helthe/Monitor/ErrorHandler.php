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

use Guzzle\Http\Message\Response;
use Helthe\Monitor\Exception\ContextErrorException;

/**
 * Error handler for Helthe Monitor
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ErrorHandler
{
    /**
     * @var string
     */
    private $reservedMemory;
    /**
     * @var Client
     */
    private $client;

    /**
     * Register the error handler.
     *
     * @param Client  $client
     * @param integer $level
     *
     * @return ErrorHandler
     */
    public static function register(Client $client, $level = null)
    {
        $handler = new self($client);

        if (null !== $level) {
            error_reporting($level);
        }

        // Disable xdebug stack traces
        if (extension_loaded('xdebug')) {
            xdebug_disable();
        }

        set_error_handler(array($handler, 'handleError'), $level);
        set_exception_handler(array($handler, 'handleException'));
        register_shutdown_function(array($handler, 'handleFatal'));
        $handler->reservedMemory = str_repeat('x', 10240);

        return $handler;
    }

    /**
     * Constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
        $this->handleException(new ContextErrorException($this->buildErrorMessage($level, $message, $file, $line), 0, $level, $file, $line, $context));

        return false;
    }

    /**
     * Handles exceptions.
     *
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        $level = E_RECOVERABLE_ERROR;

        if ($exception instanceof \ErrorException) {
            $level = $exception->getSeverity();
        }

        $this->send(array('error' => array(
            'globals' => $_SERVER,
            'level' => $level,
            'message' => $exception->getMessage(),
            'trace' => $this->getStackTrace($exception)
        )));
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
        if (!in_array($level, array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE))) {
            return;
        }

        $this->handleException(new \ErrorException($this->buildErrorMessage($level, $error['message'], $error['file'], $error['line']), 0, $level, $error['file'], $error['line']));
    }

    /**
     * Builds the error message.
     *
     * @param integer $level
     * @param string  $message
     * @param string  $file
     * @param integer $line
     *
     * @return string
     */
    private function buildErrorMessage($level, $message, $file, $line)
    {
        $levels = array(
            E_WARNING           => 'Warning',
            E_NOTICE            => 'Notice',
            E_USER_ERROR        => 'User Error',
            E_USER_WARNING      => 'User Warning',
            E_USER_NOTICE       => 'User Notice',
            E_STRICT            => 'Runtime Notice',
            E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'User Deprecated',
            E_ERROR             => 'Error',
            E_CORE_ERROR        => 'Core Error',
            E_COMPILE_ERROR     => 'Compile Error',
            E_PARSE             => 'Parse',
        );

        return sprintf('%s: %s in %s line %d', isset($levels[$level]) ? $levels[$level] : $level, $message, $file, $line);
    }

    /**
     * Get the stack trace for the given exception.
     *
     * @param \Exception $exception
     *
     * @return array
     */
    private function getStackTrace(\Exception $exception)
    {
        $trace = array();

        foreach ($exception->getTrace() as $frame) {
            if (isset($frame['file']) && isset($frame['line'])) {
                $trace[] = $this->sanitizeFrame($frame);
            }
        }

        if (empty($trace)) {
            $trace[] = $this->sanitizeFrame(array(
                'file' => $exception->getFile(),
                'line' => $exception->getLine())
            );
        }

        return $trace;
    }

    /**
     * Sanitizes the stack frame.
     *
     * @param array $frame
     *
     * @return array
     */
    private function sanitizeFrame(array $frame)
    {
        if (!isset($frame['file']) || !isset($frame['line']) || !is_readable($frame['file'])) {
            return $frame;
        }

        $sanitizedFrame = array(
            'file'     => $frame['file'],
            'line'     => $frame['line'],
            'lines'    => array(),
            'function' => isset($frame['function']) ? $frame['function'] : '',
            'class'    => isset($frame['class']) ? $frame['class'] : ''
        );

        $file = new \SplFileObject($frame['file']);
        $current = max($frame['line'] - 4, 0);
        $file->seek($current);

        while ($current <= $frame['line'] + 2 && !$file->eof()) {
            $current++;
            $sanitizedFrame['lines'][$current] = rtrim($file->current(), "\r\n");
            $file->next();
        }

        $sanitizedFrame['context'] = $this->buildContext($frame);

        return $sanitizedFrame;
    }

    /**
     * Build the stack frame context.
     *
     * @param array $frame
     *
     * @return array
     */
    private function buildContext(array $frame)
    {
        $context = array();

        if (!isset($frame['function'])) {
            return $context;
        } elseif (in_array($frame['function'], array('include', 'include_once', 'require', 'require_once'))) {
            return array(
                'file' => $frame['args'][0]
            );
        }

        if (isset($frame['class']) && method_exists($frame['class'], $frame['function'])) {
            $reflection = new \ReflectionMethod($frame['class'], $frame['function']);
        } elseif (function_exists($frame['function'])) {
            $reflection = new \ReflectionFunction($frame['function']);
        } else {
            return $context;
        }

        $params = $reflection->getParameters();

        foreach ($params as $index => $param) {
            if (isset($frame['args'][$index])) {
                $context[$param->name] = $frame['args'][$index];
            } elseif ($reflection->isUserDefined() && $param->isOptional()) {
                $context[$param->name] = $param->getDefaultValue();
            }
        }

        return $context;
    }

    /**
     * Send the error to the Helthe API.
     *
     * @param array $error
     *
     * @return Response
     */
    private function send(array $error)
    {
        return $this->client->post('/errors', null, json_encode($error))->send();
    }
}
