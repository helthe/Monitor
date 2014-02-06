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

/**
 * Sanitizes the array data of all stacktrace frames.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class SanitizeFramesPass implements TransformationPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(array $trace)
    {
        // array_values and array_filter are used to cleanout the invalid frames
        return array_values(array_filter(array_map(array($this, 'sanitize'), $trace)));
    }

    /**
     * Sanitizes a stacktrace frame.
     *
     * @param array $frame
     *
     * @return array|null
     */
    public function sanitize(array $frame)
    {
        if (!isset($frame['file']) || !isset($frame['line'])) {
            return null;
        }

        $sanitizedFrame = array(
            'file'      => $frame['file'],
            'line'      => $frame['line'],
            'function'  => isset($frame['function']) ? $frame['function'] : '',
            'class'     => isset($frame['class']) ? $frame['class'] : '',
        );

        if (isset($frame['args']) && is_array($frame['args'])) {
            $sanitizedFrame['arguments'] = $frame['args'];
        } elseif (isset($frame['arguments']) && is_array($frame['arguments'])) {
            $sanitizedFrame['arguments'] = $frame['arguments'];
        }

        return $sanitizedFrame;
    }
}
