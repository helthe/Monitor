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

use Helthe\Monitor\Report\Error\Argument;

/**
 * Transforms stacktrace arguments from arrays to objects.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ConvertArgumentsPass implements TransformationPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(array $trace)
    {
        return array_map(array($this, 'convertArguments'), $trace);
    }

    /**
     * Convert the arguments of a stacktrace frame.
     *
     * @param array $frame
     *
     * @return array
     */
    public function convertArguments(array $frame)
    {
        if (!isset($frame['args']) || !isset($frame['function']) || !is_array($frame['args'])) {
            return $frame;
        }

        $frame['args'] = $this->transformArguments(
            $frame['args'],
            $frame['function'],
            isset($frame['class']) && is_string($frame['class']) ? $frame['class'] : '',
            isset($frame['type']) && is_string($frame['type']) ? $frame['type'] : ''
        );

        return $frame;
    }

    /**
     * Transforms the arguments for the given function or class method.
     *
     * @param array  $args
     * @param string $function
     * @param string $class
     * @param string $type
     *
     * @return Argurment[]
     */
    private function transformArguments(array $args, $function, $class = '', $type = '')
    {
        $reflection = null;

        if (in_array($function, array('include', 'include_once', 'require', 'require_once'))) {
            return array('file' => new Argument($args[0]));
        }

        if (!empty($class) && method_exists($class, $function)) {
            $reflection = new \ReflectionMethod($class, $function);
        } elseif (!empty($class) && '::' == $type) {
            $reflection = new \ReflectionMethod($class, '__callStatic');
        } elseif (!empty($class) && '->' == $type) {
            $reflection = new \ReflectionMethod($class, '__call');
        } elseif (empty($class) && function_exists($function)) {
            $reflection = new \ReflectionFunction($function);
        }

        if (!$reflection instanceof \ReflectionFunctionAbstract) {
            return array();
        }

        return $this->transformReflectionArguments($reflection, $args);
    }

    /**
     * Transforms the arguments for the given ReflectionFunctionAbstract.
     *
     * @param \ReflectionFunctionAbstract $reflection
     * @param array                       $args
     *
     * @return Argument[]
     */
    private function transformReflectionArguments(\ReflectionFunctionAbstract $reflection, array $args)
    {
        $arguments = array();
        $parameters = $reflection->getParameters();

        foreach ($parameters as $index => $parameter) {
            if (isset($args[$index])) {
                $arguments[$parameter->name] = new Argument($args[$index]);
            } elseif ($parameter->isDefaultValueAvailable()) {
                $arguments[$parameter->name] = new Argument($parameter->getDefaultValue());
            }
        }

        return $arguments;
    }
}
