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
 * Interface implemented by stacktrace transformation passes.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface TransformationPassInterface
{
    /**
     * Transform the stacktrace.
     *
     * @param array $trace
     *
     * @return array
     */
    public function transform(array $trace);
}
