<?php

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
