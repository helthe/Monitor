<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Http;

use Helthe\Monitor\Http\Client;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var MockPlugin
     */
    private $mockPlugin;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->mockPlugin = new MockPlugin();
        $this->client = new Client('projet-api-key', 'https://api.helthe.co');
        $this->client->addSubscriber($this->mockPlugin);
    }

    public function testSendReportWith201Response()
    {
        $report = $this->getReportMock();

        $this->mockPlugin->addResponse(new Response(201));

        $this->client->sendReport($report);
    }

    /**
     * @expectedException Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testSendReportWith422Response()
    {
        $report = $this->getReportMock();

        $this->mockPlugin->addResponse(new Response(422));

        $this->client->sendReport($report);
    }

    /**
     * @expectedException Guzzle\Http\Exception\ServerErrorResponseException
     */
    public function testSendReportWith500Response()
    {
        $report = $this->getReportMock();

        $this->mockPlugin->addResponse(new Response(500));

        $this->client->sendReport($report);
    }

    /**
     * Get a Report mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getReportMock()
    {
        $mock = $this->getMockBuilder('Helthe\Monitor\Report\Report')
                        ->setMethods(array('jsonSerialize'))
                        ->disableOriginalConstructor()
                    ->getMock();

        $mock->expects($this->once())->method('jsonSerialize')->will($this->returnValue(array()));

        return $mock;
    }
}
