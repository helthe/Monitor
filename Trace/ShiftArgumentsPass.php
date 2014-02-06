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
 * Shifts the arguments up the stacktrace. We want the arguments to be
 * relevant to the function we are in.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ShiftArgumentsPass implements TransformationPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(array $trace)
    {
        $trace = $this->shiftArray($trace, 'args');
        $trace = $this->shiftArray($trace, 'arguments');

        return $trace;
    }

    /**
     * Shift all the array values with the key name up one level.
     *
     * @param array  $array
     * @param string $name
     *
     * @return array
     */
    private function shiftArray(array $array, $name)
    {
        for ($index = 0; $index < count($array); $index++) {
            $array = $this->shiftArrayValue($array, $name, $index);
        }

        return $array;
    }

    /**
     * Shifts the array value with the given key name at the given index.
     *
     * @param array   $array
     * @param string  $name
     * @param integer $index
     *
     * @return array
     */
    private function shiftArrayValue(array $array, $name, $index)
    {
        if (!is_array($array[$index])) {
            return $array;
        }

        unset($array[$index][$name]);

        if (isset($array[$index + 1]) && is_array($array[$index + 1]) && isset($array[$index + 1][$name])) {
            $array[$index][$name] = $array[$index + 1][$name];
        }

        return $array;
    }
}
