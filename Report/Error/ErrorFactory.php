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

use Helthe\Monitor\Exception\FatalErrorException;
use Helthe\Monitor\Trace\ChainTransformationPass;
use Helthe\Monitor\Trace\ConvertArgumentsPass;
use Helthe\Monitor\Trace\ConvertFramesPass;
use Helthe\Monitor\Trace\SanitizeFramesPass;
use Helthe\Monitor\Trace\ShiftArgumentsPass;

/**
 * Factory for creating error objects from exceptions.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ErrorFactory
{
    /**
     * Creates an error from an exception.
     *
     * @param \Exception $exception
     *
     * @return Error
     */
    public function createError(\Exception $exception)
    {
        $severity = E_RECOVERABLE_ERROR;

        if ($exception instanceof \ErrorException) {
            $severity = $exception->getSeverity();
        }

        return new Error($exception->getMessage(), $severity, get_class($exception), $this->createTrace($exception));
    }

    /**
     * Creates a stacktrace from an exception.
     *
     * @param \Exception $exception
     *
     * @return Trace
     */
    public function createTrace(\Exception $exception)
    {
        $trace = array();

        // Except for FatalErrorException, the first frame of \ErrorException stacktrace
        // refers to the error handler which is not useful.
        if (!$exception instanceof \ErrorException || $exception instanceof FatalErrorException) {
            $trace[] = $this->getExceptionFrame($exception);
        }

        return new Trace($this->transform(array_merge($trace, $exception->getTrace())));
    }

    /**
     * Generates a stacktrace frame from an exception.
     *
     * @param \Exception $exception
     *
     * @return array
     */
    private function getExceptionFrame(\Exception $exception)
    {
        return array(
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        );
    }

    /**
     * Get the stacktrace transformation pass used by the factory.
     *
     * @return ChainTransformationPass
     */
    private function getPass()
    {
        return new ChainTransformationPass(array(
            new ConvertArgumentsPass(),
            new SanitizeFramesPass(),
            new ShiftArgumentsPass(),
            new ConvertFramesPass()
        ));
    }

    /**
     * Transforms the stacktrace array.
     *
     * @param array $trace
     *
     * @return Frame[]
     */
    private function transform(array $trace)
    {
        return $this->getPass()->transform($trace);
    }
}
