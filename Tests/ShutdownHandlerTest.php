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

use Helthe\Monitor\ShutdownHandler;

class ShutdownHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testSendErrorsWithNoErrors()
    {
        $client = $this->getClientMock();
        $client->expects($this->never())->method('sendReport');

        $errorHandler = $this->getErrorHandlerMock();
        $errorHandler->expects($this->once())->method('getErrors')->will($this->returnValue(array()));

        $reportFactory = $this->getReportFactoryMock();
        $reportFactory->expects($this->never())->method('createFromErrors');

        $shutdownHandler = new ShutdownHandler($client, $errorHandler, $reportFactory);
        $shutdownHandler->sendErrors();
    }

    public function testSendErrorsWithErrors()
    {
        $client = $this->getClientMock();
        $client->expects($this->once())->method('sendReport');

        $error = $this->getErrorMock();
        $errorHandler = $this->getErrorHandlerMock();
        $errorHandler->expects($this->once())->method('getErrors')->will($this->returnValue(array($error)));

        $report = $this->getReportMock();
        $reportFactory = $this->getReportFactoryMock();
        $reportFactory->expects($this->once())->method('createFromErrors')->will($this->returnValue($report));

        $shutdownHandler = new ShutdownHandler($client, $errorHandler, $reportFactory);
        $shutdownHandler->sendErrors();
    }

    /**
     * Get a Client mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getClientMock()
    {
        return $this->getMock('Helthe\Monitor\Client\ClientInterface');
    }

    /**
     * Get a Error mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getErrorMock()
    {
        return $this->getMockBuilder('Helthe\Monitor\Report\Error')
                        ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Get a ErrorHandler mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getErrorHandlerMock()
    {
        return $this->getMockBuilder('Helthe\Monitor\ErrorHandler')
                        ->setMethods(array('getErrors'))
                        ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Get a Report mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getReportMock()
    {
        return $this->getMockBuilder('Helthe\Monitor\Report\Report')
                        ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Get a ReportFactory mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getReportFactoryMock()
    {
        return $this->getMockBuilder('Helthe\Monitor\Report\ReportFactory')
                        ->setMethods(array('createFromErrors'))
                        ->disableOriginalConstructor()
                    ->getMock();
    }
}
