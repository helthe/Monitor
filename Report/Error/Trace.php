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
 * A PHP exception stacktace.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Trace implements \JsonSerializable
{
    /**
     * Stacktrace
     *
     * @var Frame[]
     */
    private $trace;

    /**
     * Constructor.
     *
     * @param Frame[] $frames
     */
    public function __construct(array $frames)
    {
        $this->trace = array();

        foreach ($frames as $frame) {
            $this->addFrame($frame);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->trace;
    }

    /**
     * Add a stacktrace frame.
     *
     * @param Frame $frame
     */
    private function addFrame(Frame $frame)
    {
        $this->trace[] = $frame;
    }
}
