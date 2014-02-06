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

use Helthe\Monitor\Http\Client;
use Helthe\Monitor\Report\Application;
use Helthe\Monitor\Report\Error\ErrorFactory;
use Helthe\Monitor\Report\ReportFactory;
use Helthe\Monitor\Report\Request;
use Helthe\Monitor\Report\Server;
use Symfony\Component\Config\Definition\Processor;

/**
 * Facade for Helthe Monitor. Registers all the handlers.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Monitor
{
    /**
     * Enabled flag.
     *
     * @var Boolean
     */
    private static $enabled;

    /**
     * Enables Helthe Monitor.
     *
     * @param array $configs
     */
    public static function enable(array $configs)
    {
        if (static::$enabled || 'cli' === php_sapi_name()) {
            return;
        }

        static::$enabled = true;
        $processor = new Processor();
        // Configs need to be wrapper in an additional array for the processor
        $configs = $processor->processConfiguration(new Configuration(), array($configs));

        $client = self::createClient($configs);
        $errorHandler = self::registerErrorHandler($configs['error_level']);

        self::registerShutdownHandler($client, $errorHandler, $configs['application']);
    }

    /**
     * Creates a HTTP client instance.
     *
     * @param array $configs
     *
     * @return Client
     */
    private static function createClient(array $configs)
    {
        return new Client($configs['api_key'], $configs['endpoint']);
    }

    /**
     * Create an Application instance.
     *
     * @param array $application
     *
     * @return Application
     */
    private static function createApplication(array $application)
    {
        return new Application(
            $application['root_directory'],
            isset($application['version']) ? $application['version'] : '',
            isset($application['context']) ? $application['context'] : array()
        );
    }

    /**
     * Registers a error handler.
     *
     * @param integer $configs
     *
     * @return ErrorHandler
     */
    private static function registerErrorHandler($level)
    {
        return ErrorHandler::register(new ErrorFactory(), $level);
    }

    /**
     * Registers a shutdown handler.
     *
     * @param Client       $client
     * @param ErrorHandler $handler
     * @param array        $application
     *
     * @return ShutdownHandler
     */
    private static function registerShutdownHandler(Client $client, ErrorHandler $handler, array $application)
    {
        return ShutdownHandler::register($client, $handler, new ReportFactory(
            self::createApplication($application),
            Request::createFromGlobals(),
            Server::createFromGlobals()
        ));
    }
}
