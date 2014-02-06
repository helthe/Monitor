<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Trace;

use Helthe\Monitor\Report\Error\Frame;

/**
 * Transforms stacktrace frames from arrays to objects.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ConvertFramesPass implements TransformationPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(array $trace)
    {
        // array_values and array_filter are used to cleanout the invalid frames
        return array_values(array_filter(array_map(array($this, 'convertFrame'), $trace)));
    }

    /**
     * Converts a stacktrace frame associative array into an object.
     *
     * @param array $frame
     *
     * @return Frame|null
     */
    public function convertFrame(array $frame)
    {
        if (!isset($frame['file']) || !isset($frame['line'])) {
            return null;
        }

        $arguments = array();

        if (isset($frame['args']) && is_array($frame['args'])) {
            $arguments = $frame['args'];
        } elseif (isset($frame['arguments']) && is_array($frame['arguments'])) {
            $arguments = $frame['arguments'];
        }

        return new Frame(
            $frame['file'],
            $frame['line'],
            isset($frame['function']) ? $frame['function'] : '',
            isset($frame['class']) ? $frame['class'] : '',
            $arguments
        );
    }
}
