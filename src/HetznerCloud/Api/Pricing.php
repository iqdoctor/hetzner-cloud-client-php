<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Api;

/**
 * Pricing.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Pricing extends AbstractApi
{
    /**
     * Returns prices for all resources available on the platform. VAT and currency of the project owner are used for
     * calculations. Both net and gross prices are included in the response.
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function all()
    {
        return $this->get('/pricing');
    }
}
