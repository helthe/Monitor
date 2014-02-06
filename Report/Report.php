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
 * Helthe Error Report.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Report implements \JsonSerializable
{
    /**
     * Application details.
     *
     * @var Application
     */
    private $application;

    /**
     * Errors being sent in the report.
     *
     * @var Error[]
     */
    private $errors;

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
     * @param Error[]     $errors
     * @param Request     $request
     * @param Server      $server
     */
    public function __construct(Application $application, array $errors, Request $request, Server $server)
    {
        $this->application = $application;
        $this->errors = array();
        $this->request = $request;
        $this->server = $server;

        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * Add an error to the report.
     *
     * @param Error $error
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'errors'      => $this->errors,
            'application' => $this->application,
            'request'     => $this->request,
            'server'      => $this->server
        );
    }
}
