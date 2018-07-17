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

use HetznerCloud\Api\Images\Actions;

/**
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ImagesTest extends TestCase
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
    public function shouldGetAllImages()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/images', ['page' => 2, 'per_page' => 5])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['page' => 2, 'per_page' => 5]));
    }

    /**
     * @test
     */
    public function shouldShowImage()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/images/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1));
    }

    /**
     * @test
     */
    public function shouldUpdateImage()
    {
        $expectedArray = ['foobar'];
        $params = ['description' => 'description', 'type' => 'type'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with('/images/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->update(1, $params));
    }

    /**
     * @test
     */
    public function shouldRemoveImage()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('/images/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->remove(1));
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\Images';
    }
}
