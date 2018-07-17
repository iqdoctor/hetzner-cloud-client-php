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

use HetznerCloud\Api\Actions;
use HetznerCloud\Api\Datacenters;
use HetznerCloud\Api\FloatingIps;
use HetznerCloud\Api\Images;
use HetznerCloud\Api\Isos;
use HetznerCloud\Api\Locations;
use HetznerCloud\Api\Pricing;
use HetznerCloud\Api\Servers;
use HetznerCloud\Api\ServerTypes;
use HetznerCloud\Api\SshKeys;
use HetznerCloud\Client;
use HetznerCloud\HttpClient\Plugin\Authentication;

/**
 * ClientTest.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldAuthenticateUsingToken()
    {
        $token = 'foobar';

        $builder = $this->getMockBuilder(\HetznerCloud\HttpClient\Builder::class)
            ->setMethods(['addPlugin', 'removePlugin'])
            ->disableOriginalConstructor()
            ->getMock();
        $builder->expects($this->once())
            ->method('addPlugin')
            ->with($this->equalTo(new Authentication($token)));
        $builder->expects($this->once())
            ->method('removePlugin')
            ->with(Authentication::class);

        $client = $this->getMockBuilder(\HetznerCloud\Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHttpClientBuilder'])
            ->getMock();
        $client->expects($this->any())
            ->method('getHttpClientBuilder')
            ->willReturn($builder);

        $client->authenticate($token);
    }

    /**
     * @test
     */
    public function shouldCreateStaticInstance()
    {
        $this->assertInstanceOf(Client::class, Client::create());
    }

    /**
     * @test
     */
    public function shouldReturnActions()
    {
        $this->assertInstanceOf(Actions::class, Client::create()->actions());
    }

    /**
     * @test
     */
    public function shouldReturnDatacenters()
    {
        $this->assertInstanceOf(Datacenters::class, Client::create()->datacenters());
    }

    /**
     * @test
     */
    public function shouldReturnFloatingIps()
    {
        $this->assertInstanceOf(FloatingIps::class, Client::create()->floatingIps());
    }

    /**
     * @test
     */
    public function shouldReturnImages()
    {
        $this->assertInstanceOf(Images::class, Client::create()->images());
    }

    /**
     * @test
     */
    public function shouldReturnIsos()
    {
        $this->assertInstanceOf(Isos::class, Client::create()->isos());
    }

    /**
     * @test
     */
    public function shouldReturnLocations()
    {
        $this->assertInstanceOf(Locations::class, Client::create()->locations());
    }

    /**
     * @test
     */
    public function shouldReturnSshKeys()
    {
        $this->assertInstanceOf(SshKeys::class, Client::create()->sshKeys());
    }

    /**
     * @test
     */
    public function shouldReturnPricing()
    {
        $this->assertInstanceOf(Pricing::class, Client::create()->pricing());
    }

    /**
     * @test
     */
    public function shouldReturnServers()
    {
        $this->assertInstanceOf(Servers::class, Client::create()->servers());
    }

    /**
     * @test
     */
    public function shouldReturnServerTypes()
    {
        $this->assertInstanceOf(ServerTypes::class, Client::create()->serverTypes());
    }
}
