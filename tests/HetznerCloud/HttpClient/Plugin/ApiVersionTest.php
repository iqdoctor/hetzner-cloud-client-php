<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Tests\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HetznerCloud\HttpClient\Plugin\ApiVersion;
use Http\Client\Promise\HttpFulfilledPromise;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ApiVersionTest extends TestCase
{
    /**
     * @test
     */
    public function callNextCallback()
    {
        $request = new Request('GET', '');
        $plugin = new ApiVersion();
        $promise = new HttpFulfilledPromise(new Response());

        $callback = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['next'])
            ->getMock();
        $callback->expects($this->once())
            ->method('next')
            ->with($this->isInstanceOf(RequestInterface::class))
            ->willReturn($promise);

        $this->assertEquals(
            $promise,
            $plugin->handleRequest(
                $request,
                [$callback, 'next'],
                function () {
                }
            )
        );
    }

    /**
     * @test
     */
    public function prefixRequestPath()
    {
        $request = new Request('GET', '/servers');
        $expected = new Request('GET', '/v1/servers');
        $plugin = new ApiVersion();

        $callback = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['next'])
            ->getMock();
        $callback->expects($this->once())
            ->method('next')
            ->with($expected);

        $plugin->handleRequest(
            $request,
            [$callback, 'next'],
            function () {
            }
        );
    }

    /**
     * @test
     */
    public function noPrefixingRequired()
    {
        $request = new Request('GET', '/v1/servers');
        $plugin = new ApiVersion();

        $callback = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['next'])
            ->getMock();
        $callback->expects($this->once())
            ->method('next')
            ->with($request);

        $plugin->handleRequest(
            $request,
            [$callback, 'next'],
            function () {
            }
        );
    }
}
