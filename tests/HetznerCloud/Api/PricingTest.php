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
class PricingTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllPricing()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/pricing')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all());
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\Pricing';
    }
}
