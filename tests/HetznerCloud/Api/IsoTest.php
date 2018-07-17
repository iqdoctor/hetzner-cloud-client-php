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

/**
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class IsoTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllIsos()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/isos', ['page' => 2, 'per_page' => 5])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['page' => 2, 'per_page' => 5]));
    }

    /**
     * @test
     */
    public function shouldShowIso()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/isos/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1));
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\Isos';
    }
}
