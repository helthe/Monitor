<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Client;

use Helthe\Monitor\Report\Report;

/**
 * Interface for Helthe Monitor Clients to communicate with the Helthe API.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface ClientInterface
{
    /**
     * Send a report to Helthe.
     *
     * @param Report $report
     */
    public function sendReport(Report $report);
}
