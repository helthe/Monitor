<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Http;

use Guzzle\Service\Client as GuzzleClient;
use Helthe\Monitor\Client\ClientInterface;
use Helthe\Monitor\Report\Report;

/**
 * The Helthe Monitor Client communicates with the Helthe API via HTTP.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Client extends GuzzleClient implements ClientInterface
{
    /**
     * Client version.
     *
     * @var string
     */
    const VERSION = 'alpha';

    /**
     * Constructor.
     *
     * @param string $apiKey
     * @param string $endpointUrl
     *
     * @throws \RuntimeException
     */
    public function __construct($apiKey, $endpointUrl)
    {
        parent::__construct($endpointUrl);

        $this->setUserAgent('helthe-monitor/'. self::VERSION, true);

        // Plugins
        $this->addSubscriber(new AuthenticationPlugin($apiKey));

        $this->setDefaultOption('headers/Accept', 'application/json');
        $this->setDefaultOption('headers/Content-type', 'application/json');
    }

    /**
     * {@inheritdoc}
     */
    public function sendReport(Report $report)
    {
        $this->post('/reports', null, json_encode($report))->send();
    }
}
