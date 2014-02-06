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
 * Calls multiple stacktrace transformation passes in a chain.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ChainTransformationPass implements TransformationPassInterface
{
    /**
     * Transformation passes in the chain.
     *
     * @var TransformationPassInterface[]
     */
    private $passes;

    /**
     * Constructor.
     *
     * @param TransformationPassInterface[] $passes
     */
    public function __construct(array $passes)
    {
        $this->passes = array();

        foreach ($passes as $pass) {
            $this->addPass($pass);
        }
    }

    /**
     * Adds a transformation pass to the chain.
     *
     * @param TransformationPassInterface $pass
     */
    public function addPass(TransformationPassInterface $pass)
    {
        $this->passes[] = $pass;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(array $trace)
    {
        foreach ($this->passes as $pass) {
            $trace = $pass->transform($trace);
        }

        return $trace;
    }
}
