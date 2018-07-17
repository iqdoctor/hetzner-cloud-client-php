<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Tests\HttpClient;

use HetznerCloud\HttpClient\Builder;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;

/**
 * @author Fabien Bourigault <bourigaultfabien@gmail.com>
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Builder
     */
    private $subject;

    public function setUp()
    {
        $this->subject = new Builder(
            $this->getMockBuilder(HttpClient::class)->getMock(),
            $this->getMockBuilder(RequestFactory::class)->getMock(),
            $this->getMockBuilder(StreamFactory::class)->getMock()
        );
    }

    /**
     * @test
     */
    public function addPluginShouldInvalidateHttpClient()
    {
        $client = $this->subject->getHttpClient();

        $this->subject->addPlugin($this->getMockBuilder(Plugin::class)->getMock());

        $this->assertNotSame($client, $this->subject->getHttpClient());
    }

    /**
     * @test
     */
    public function removePluginShouldInvalidateHttpClient()
    {
        $this->subject->addPlugin($this->getMockBuilder(Plugin::class)->getMock());

        $client = $this->subject->getHttpClient();

        $this->subject->removePlugin(Plugin::class);

        $this->assertNotSame($client, $this->subject->getHttpClient());
    }

    /**
     * @test
     */
    public function httpClientShouldBeAnHttpMethodsClient()
    {
        $this->assertInstanceOf(HttpMethodsClient::class, $this->subject->getHttpClient());
    }
}
