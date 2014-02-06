<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests;

use Helthe\Monitor\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int Error reporting level before running tests.
     */
    private $errorReporting;

    /**
     * @var string Display errors setting before running tests.
     */
    private $displayErrors;

    public function setUp()
    {
        $this->errorReporting = error_reporting(E_ALL | E_STRICT);
        $this->displayErrors = ini_get('display_errors');
        ini_set('display_errors', '1');
    }

    public function tearDown()
    {
        ini_set('display_errors', $this->displayErrors);
        error_reporting($this->errorReporting);
        restore_error_handler();
        restore_exception_handler();
    }

    public function testConstruct()
    {
        $factory = $this->getErrorFactoryMock();
        $factory->expects($this->never())->method('createError');

        ErrorHandler::register($factory, E_ALL);

        $this->assertEquals(E_ALL, error_reporting());
    }

    public function testDoesNotHandleError()
    {
        $factory = $this->getErrorFactoryMock();
        $factory->expects($this->never())->method('createError');

        $handler = ErrorHandler::register($factory, E_WARNING);

        trigger_error('foo');

        $this->assertCount(0, $handler->getErrors());
    }

    public function testHandleError()
    {
        $that = $this;
        $errorCheck = function ($exception) use ($that) {
            $that->assertInstanceOf('\ErrorException', $exception);
            $that->assertEquals(E_NOTICE, $exception->getSeverity());
            $that->assertEquals('unknown', $exception->getFile());
            $that->assertEquals(0, $exception->getLine());
            $that->assertEquals('foo', $exception->getMessage());
        };

        $factory = $this->getErrorFactoryMock();
        $factory->expects($this->once())->method('createError')->will($this->returnCallback($errorCheck));

        $handler = ErrorHandler::register($factory);
        $handler->handleError(E_NOTICE, 'foo');

        $this->assertCount(1, $handler->getErrors());
    }

    public function testHandleException()
    {
        $that = $this;
        $exceptionCheck = function ($exception) use ($that) {
            $that->assertInstanceOf('\InvalidArgumentException', $exception);
            $that->assertEquals(__FILE__, $exception->getFile());
            $that->assertEquals(__LINE__ + 8, $exception->getLine());
            $that->assertEquals('bar', $exception->getMessage());
        };

        $factory = $this->getErrorFactoryMock();
        $factory->expects($this->once())->method('createError')->will($this->returnCallback($exceptionCheck));

        $handler = ErrorHandler::register($factory);
        $handler->handleException(new \InvalidArgumentException('bar'));

        $this->assertCount(1, $handler->getErrors());
    }

    public function testHandleMultiple()
    {
        $that = $this;
        $errorCheck = function ($exception) use ($that) {
            $that->assertInstanceOf('\ErrorException', $exception);
            $that->assertEquals(E_ERROR, $exception->getSeverity());
            $that->assertEquals('foo.php', $exception->getFile());
            $that->assertEquals(13, $exception->getLine());
            $that->assertEquals('bar', $exception->getMessage());
        };

        $exceptionCheck = function ($exception) use ($that) {
            $that->assertInstanceOf('\Exception', $exception);
            $that->assertEquals(__FILE__, $exception->getFile());
            $that->assertEquals(__LINE__ + 10, $exception->getLine());
            $that->assertEquals('foo', $exception->getMessage());
        };

        $factory = $this->getErrorFactoryMock();
        $factory->expects($this->at(0))->method('createError')->will($this->returnCallback($errorCheck));
        $factory->expects($this->at(1))->method('createError')->will($this->returnCallback($exceptionCheck));

        $handler = ErrorHandler::register($factory);
        $handler->handleError(E_ERROR, 'bar', 'foo.php', 13);
        $handler->handleException(new \Exception('foo'));

        $this->assertCount(2, $handler->getErrors());
    }

    /**
     * Get a ErrorFactory mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getErrorFactoryMock()
    {
        return $this->getMock('Helthe\Monitor\Report\Error\ErrorFactory', array('createError'));
    }
}
