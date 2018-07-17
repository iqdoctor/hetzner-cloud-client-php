<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Tests\Api;

use HetznerCloud\Api\FloatingIps\Actions;

/**
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class FloatingIpsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnActionsInstance()
    {
        $api = $this->getApiMock();

        $this->assertInstanceOf(Actions::class, $api->actions());
    }

    /**
     * @test
     */
    public function shouldGetAllFloatingIps()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/floating_ips', ['page' => 2, 'per_page' => 5])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['page' => 2, 'per_page' => 5]));
    }

    /**
     * @test
     */
    public function shouldShowFloatingIp()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/floating_ips/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1));
    }

    /**
     * @test
     */
    public function shouldCreateFloatingIpWithHomeLocation()
    {
        $expectedArray = ['foobar'];
        $params = [
            'type' => 'ipv4',
            'home_location' => 'location',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/floating_ips')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->create($params));
    }

    /**
     * @test
     */
    public function shouldCreateFloatingIpWithServer()
    {
        $expectedArray = ['foobar'];
        $params = [
            'type' => 'ipv4',
            'server' => 123,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/floating_ips')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->create($params));
    }

    /**
     * @test
     */
    public function shouldUpdateFloatingIp()
    {
        $expectedArray = ['foobar'];
        $params = ['description' => 'description'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with('/floating_ips/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->update(1, $params));
    }

    /**
     * @test
     */
    public function shouldRemoveFloatingIp()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('/floating_ips/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->remove(1));
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\FloatingIps';
    }
}
