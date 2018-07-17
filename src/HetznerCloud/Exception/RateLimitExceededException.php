<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Exception;

/**
 * RateLimitExceededException.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class RateLimitExceededException extends RuntimeException
{
    private $limit;
    private $reset;

    public function __construct($limit = 3600, $reset = 1800, $code = 0, $previous = null)
    {
        $this->limit = (int)$limit;
        $this->reset = (int)$reset;

        parent::__construct(sprintf('You have reached Hetzner Cloud hourly limit! Actual limit is: %d', $limit), $code, $previous);
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getResetTime()
    {
        return $this->reset;
    }
}
