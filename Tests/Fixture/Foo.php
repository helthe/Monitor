<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Fixture;

class Foo
{
    /**
     * {@inheritdoc}
     */
    public static function __callStatic($name, $arguments)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $arguments)
    {
    }

    private function bar($foo, $bar = 'bar')
    {
    }
}
