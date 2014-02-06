<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Report;

use Helthe\Monitor\Report\Error\Error;

/**
 * Factory for creating reports from errors.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ReportFactory
{
    /**
     * Application details.
     *
     * @var Application
     */
    private $application;

    /**
     * Request details.
     *
     * @var Request
     */
    private $request;

    /**
     * Server details.
     *
     * @var Server
     */
    private $server;

    /**
     * Constructor.
     *
     * @param Application $application
     * @param Request     $request
     * @param Server      $server
     */
    public function __construct(Application $application, Request $request, Server $server)
    {
        $this->application = $application;
        $this->request = $request;
        $this->server = $server;
    }

    /**
     * Creates a report
     *
     * @param Error[] $errors
     *
     * @return Report
     */
    public function createFromErrors(array $errors)
    {
        return new Report($this->application, $errors, $this->request, $this->server);
    }
}
