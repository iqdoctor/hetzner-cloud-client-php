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

use Http\Client\Common\Plugin\Journal;
use Http\Client\Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A plugin to remember the last response.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class History implements Journal
{
    /**
     * @var ResponseInterface
     */
    private $lastResponse;

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function addSuccess(RequestInterface $request, ResponseInterface $response)
    {
        $this->lastResponse = $response;
    }

    /**
     * @param RequestInterface $request
     * @param Exception $exception
     */
    public function addFailure(RequestInterface $request, Exception $exception)
    {
    }
}
