<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

/**
 * Prefix requests path with /v1/ if required.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ApiVersion implements Plugin
{
    const API_VERSION = 'v1';

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $uri = $request->getUri();

        if (substr($uri->getPath(), 0, 4) !== '/' . static::API_VERSION . '/') {
            $request = $request->withUri($uri->withPath('/' . static::API_VERSION . '' . $uri->getPath()));
        }

        return $next($request);
    }
}
