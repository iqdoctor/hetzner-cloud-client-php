<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Tests;

use GuzzleHttp\Psr7\Response;
use HetznerCloud\Api\ApiInterface;
use HetznerCloud\Client;
use HetznerCloud\HttpClient\Plugin\History;
use HetznerCloud\ResultPager;
use Http\Client\Common\HttpMethodsClient;
use PHPUnit\Framework\TestCase;

/**
 * ResultPagerTest.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ResultPagerTest extends TestCase
{
    /**
     * @test
     */
    public function fetch()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $api = $this->getMockBuilder(ApiInterface::class)
            ->setMethods(['__construct', 'all'])
            ->getMock();
        $api->expects($this->once())
            ->method('all')
            ->willReturn(['server1', 'server2']);

        $pager = new ResultPager($client);

        $result = $pager->fetch($api, 'all');

        $this->assertEquals(['server1', 'server2'], $result);
    }

    /**
     * @test
     */
    public function fetchAll()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $history = $this->getMockBuilder(History::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response1 = (new Response)->withHeader('Link', '<https://api.hetzner.cloud/servers?page=2>; rel=next,');
        $response2 = (new Response)->withHeader('Link', '<https://api.hetzner.cloud/servers?page=3>; rel=next,')
            ->withHeader('Content-Type', 'application/json')
            ->withBody(\GuzzleHttp\Psr7\stream_for(json_encode(['servers' => ['server3', 'server4']])));
        $response3 = (new Response)->withHeader('Content-Type', 'application/json')
            ->withBody(\GuzzleHttp\Psr7\stream_for(json_encode(['servers' => ['server5', 'server6']])));

        $history
            ->method('getLastResponse')
            ->will(
                $this->onConsecutiveCalls(
                    $response1,
                    $response1,
                    $response1,
                    $response2,
                    $response2,
                    $response2,
                    $response3
                )
            );

        $httpClient = $this->getMockBuilder(HttpMethodsClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $httpClient->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                ['https://api.hetzner.cloud/servers?page=2'],
                ['https://api.hetzner.cloud/servers?page=3']
            )
            ->will(
                $this->onConsecutiveCalls(
                    $response2,
                    $response3
                )
            );

        $client
            ->method('getResponseHistory')
            ->willReturn($history);
        $client
            ->method('getHttpClient')
            ->willReturn($httpClient);

        $api = $this->getMockBuilder(ApiInterface::class)
            ->setMethods(['__construct', 'all'])
            ->getMock();
        $api->expects($this->exactly(1))
            ->method('all')
            ->willReturn(['server1', 'server2']);

        $pager = new ResultPager($client);

        $result = $pager->fetchAll($api, 'all');

        $this->assertEquals(['server1', 'server2', 'server3', 'server4', 'server5', 'server6'], $result);
    }
}
