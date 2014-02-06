<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor;

use Helthe\Monitor\Client\ClientInterface;
use Helthe\Monitor\Report\ReportFactory;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Shutdown handler that sends out all the collected errors when the PHP process ends.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ShutdownHandler
{
    /**
     * HTTP client.
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * Error handler.
     *
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * Report factory.
     *
     * @var ReportFactory
     */
    private $reportFactory;

    /**
     * Registers the shutdown handler.
     *
     * @param ClientInterface $client
     * @param ErrorHandler    $errorHandler
     * @param ReportFactory   $reportFactory
     */
    public static function register(ClientInterface $client, ErrorHandler $errorHandler, ReportFactory $reportFactory)
    {
        $handler = new self($client, $errorHandler, $reportFactory);

        register_shutdown_function(array($handler, 'sendErrors'));

        return $handler;
    }

    /**
     * Constructor.
     *
     * @param ClientInterface $client
     * @param ErrorHandler    $errorHandler
     * @param ReportFactory   $reportFactory
     */
    public function __construct(ClientInterface $client, ErrorHandler $errorHandler, ReportFactory $reportFactory)
    {
        $this->client = $client;
        $this->errorHandler = $errorHandler;
        $this->reportFactory = $reportFactory;
    }

    /**
     * Sends all the errors collected by the error handler.
     */
    public function sendErrors()
    {
        $errors = $this->errorHandler->getErrors();

        if (empty($errors)) {
            return;
        }

        $report = $this->reportFactory->createFromErrors($errors);

        try {
            $this->client->sendReport($report);
        } catch (BadResponseException $exception) {
            $label = 'Error';
            $request = $exception->getRequest();
            $response = $exception->getResponse();

            if ($response->isClientError()) {
                $label = 'Client error';
            } elseif ($response->isServerError()) {
                $label = 'Server error';
            }

            error_log(sprintf('%s "%s" with status code %i at %s', $label, $response->getReasonPhrase(), $response->getStatusCode(), $request->getUrl()));
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }
    }
}
