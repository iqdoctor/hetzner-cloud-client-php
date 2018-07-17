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

use HetznerCloud\Api\Servers\Actions;

/**
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ServersTest extends TestCase
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
    public function shouldGetAllServersWithNameParam()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers', ['page' => 2, 'per_page' => 5, 'name' => 'test'])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['page' => 2, 'per_page' => 5, 'name' => 'test']));
    }

    /**
     * @test
     */
    public function shouldGetAllServersWithoutParams()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all());
    }

    /**
     * @test
     */
    public function shouldShowServer()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1));
    }

    /**
     * @test
     */
    public function shouldCreateServerWithAllParams()
    {
        $expectedArray = ['foobar'];
        $params = [
            'name' => 'name',
            'server_type' => 'type',
            'start_after_create' => true,
            'image' => 'image',
            'ssh_keys' => ['ssh_key'],
            'user_data' => 'user data',
            'location' => 'location',
            'datacenter' => 'datacenter',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->create($params));
    }

    /**
     * @test
     */
    public function shouldCreateServerWithAllOnlyRequiredParams()
    {
        $expectedArray = ['foobar'];
        $params = [
            'name' => 'name',
            'server_type' => 'type',
            'image' => 'image',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->create($params));
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function shouldNotCreateServerWithoutRequiredParams()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('post');

        $api->create([]);
    }

    /**
     * @test
     */
    public function shouldUpdateServer()
    {
        $expectedArray = ['foobar'];
        $params = ['name' => 'name'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with('/servers/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->update(1, $params));
    }

    /**
     * @test
     */
    public function shouldRemoveServer()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('/servers/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->remove(1));
    }

    /**
     * @test
     */
    public function shouldShowMetrics()
    {
        $expectedArray = ['foobar'];
        $params = [
            'type' => 'cpu',
            'start' => new \DateTime('-30 days'),
            'end' => new \DateTime('now'),
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers/1/metrics')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->metrics(1, $params));
    }

    /**
     * @test
     */
    public function shouldShowAllMetrics()
    {
        $expectedArray = ['foobar'];
        $params = [
            'type' => 'cpu,disk,network',
            'start' => new \DateTime('-30 days'),
            'end' => new \DateTime('now'),
            'step' => 1,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers/1/metrics')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->metrics(1, $params));
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\Servers';
    }
}
