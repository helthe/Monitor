<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Exception;

/**
 * Fatal Error Exception distiguishes fatal errors from regular error exceptions.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class FatalErrorException extends \ErrorException
{
}
