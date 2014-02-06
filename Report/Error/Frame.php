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
 * A PHP stacktrace frame.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Frame implements \JsonSerializable
{
    /**
     * The file location in the frame.
     *
     * @var string
     */
    private $file;

    /**
     * The line number in the frame.
     *
     * @var integer
     */
    private $line;

    /**
     * Class name referenced in the frame.
     *
     * @var string
     */
    private $class;

    /**
     * Function name referenced in the frame.
     *
     * @var string
     */
    private $function;

    /**
     * Arguments available within the frame.
     *
     * @var Argument[]
     */
    private $arguments;

    /**
     * Source code lines from the location referenced in the frame. The array index is the line number in the file.
     *
     * @var array
     */
    private $lines;

    /**
     * Constructor.
     *
     * @param string     $file
     * @param integer    $line
     * @param string     $function
     * @param string     $class
     * @param Argument[] $arguments
     */
    public function __construct($file, $line, $function = '', $class = '', array $arguments = array())
    {
        if (!empty($class) && empty($function)) {
            throw new \InvalidArgumentException('A stack frame requires a function name with a class');
        }

        $this->file = $file;
        $this->line = $line;
        $this->class = $class;
        $this->function = $function;
        $this->arguments = array();
        $this->lines = $this->getSourceCodeLines($file, $line);

        foreach ($arguments as $name => $argument) {
            $this->addArgument($name, $argument);
        }
    }

    /**
     * Add an argument to the stack frame.
     *
     * @param string   $name
     * @param Argument $argument
     */
    public function addArgument($name, Argument $argument)
    {
        $this->arguments[$name] = $argument;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'file'      => $this->file,
            'line'      => $this->line,
            'function'  => $this->function,
            'class'     => $this->class,
            'lines'     => $this->lines,
            'arguments' => $this->arguments
        );
    }

    /**
     * Get the source code lines surrounding the given line in the given file.
     *
     * @param string  $file
     * @param integer $line
     *
     * @return array
     */
    private function getSourceCodeLines($file, $line)
    {
        $lines = array();

        if (!is_readable($file)) {
            return $lines;
        }

        try {
            $file = new \SplFileObject($file);
            // This extra finery is due to seek being zero-based.
            $current = max($line - 4, 0);
            $file->seek($current);

            while ($current <= $line + 2 && !$file->eof()) {
                $current++;
                $lines[$current] = rtrim($file->current(), "\r\n");
                $file->next();
            }
        } catch (\Exception $exception) {
            $lines = array();
        }

        return $lines;
    }
}
