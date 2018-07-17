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

use HetznerCloud\Client;

/**
 * ApiInterface.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
interface ApiInterface
{
    public function __construct(Client $client);
}
