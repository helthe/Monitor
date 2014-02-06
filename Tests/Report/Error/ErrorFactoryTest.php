<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Report\Error;

use Helthe\Monitor\Report\Error\ErrorFactory;

class ErrorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ErrorFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new ErrorFactory();
    }

    public function testCreateErrorFromException()
    {
        $error = $this->factory->createError($this->getExceptionMock('foo', 'bar.php', 13));

        $this->assertEquals('{"message":"foo","severity":4096,"trace":[{"file":"bar.php","line":13,"function":"","class":"","lines":[],"arguments":[]}],"type":"Exception"}', json_encode($error));
    }

    public function testCreateErrorFromErrorException()
    {
        $error = $this->factory->createError($this->getErrorExceptionMock('foo', 'bar.php', 13));

        $this->assertEquals('{"message":"foo","severity":1,"trace":[],"type":"ErrorException"}', json_encode($error));
    }

    public function testCreateTraceFromException()
    {
        $error = $this->factory->createTrace($this->getExceptionMock('foo', 'bar.php', 13));

        $this->assertEquals('[{"file":"bar.php","line":13,"function":"","class":"","lines":[],"arguments":[]}]', json_encode($error));
    }

    public function testCreateTraceFromErrorException()
    {
        $error = $this->factory->createTrace($this->getErrorExceptionMock('foo', 'bar.php', 13));

        $this->assertEquals('[]', json_encode($error));
    }

    /**
     * Get a modified Exception object that is mocked with the required properties.
     *
     * @param string  $message
     * @param string  $file
     * @param integer $line
     * @param array   $trace
     *
     * @return \Exception
     */
    private function getExceptionMock($message, $file, $line, array $trace = array())
    {
        $exception = new \Exception($message);
        $reflection = new \ReflectionObject($exception);

        $fileProperty = $reflection->getProperty('file');
        $fileProperty->setAccessible(true);
        $fileProperty->setValue($exception, $file);

        $lineProperty = $reflection->getProperty('line');
        $lineProperty->setAccessible(true);
        $lineProperty->setValue($exception, $line);

        $traceProperty = $reflection->getProperty('trace');
        $traceProperty->setAccessible(true);
        $traceProperty->setValue($exception, $trace);

        return $exception;
    }

    /**
     * Get a modified ErrorException object that is mocked with the required properties.
     *
     * @param string  $message
     * @param string  $file
     * @param integer $line
     * @param array   $trace
     *
     * @return \ErrorException
     */
    private function getErrorExceptionMock($message, $file, $line, array $trace = array())
    {
        $exception = new \ErrorException($message);
        $reflection = new \ReflectionObject($exception);

        $fileProperty = $reflection->getProperty('file');
        $fileProperty->setAccessible(true);
        $fileProperty->setValue($exception, $file);

        $lineProperty = $reflection->getProperty('line');
        $lineProperty->setAccessible(true);
        $lineProperty->setValue($exception, $line);

        $traceProperty = $reflection->getParentClass()->getProperty('trace');
        $traceProperty->setAccessible(true);
        $traceProperty->setValue($exception, $trace);

        return $exception;
    }
}
