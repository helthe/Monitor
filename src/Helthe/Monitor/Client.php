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

use Guzzle\Http\Client as GuzzleClient;

/**
 * The Helthe Monitor Client communicates with the Helthe API via HTTP.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Client extends GuzzleClient
{
    /**
     * Constructor.
     *
     * @param string $baseUrl
     * @param string $apiKey
     *
     * @throws \RuntimeException
     */
    public function __construct($baseUrl, $apiKey)
    {
        parent::__construct($baseUrl);

        $this->setDefaultOption('headers/helthe-api-key', $apiKey);
        $this->setDefaultOption('headers/Accept', 'application/json');
        $this->setDefaultOption('headers/Content-type', 'application/json');
    }
}
