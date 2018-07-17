<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Tests\Api\Images;

use HetznerCloud\Tests\Api\TestCase;

/**
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ActionsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllImageActions()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/images/1/actions', ['page' => 2, 'per_page' => 5])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(1, ['page' => 2, 'per_page' => 5]));
    }

    /**
     * @test
     * @dataProvider getSortProvider
     */
    public function shouldGetAllImageActionsSorted($sort)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/images/1/actions', ['sort' => $sort])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(1, ['sort' => $sort]));
    }

    public function getSortProvider()
    {
        yield ['id'];
        yield ['id:asc'];
        yield ['id:desc'];
        yield ['command'];
        yield ['command:asc'];
        yield ['command:desc'];
        yield ['status'];
        yield ['status:asc'];
        yield ['status:desc'];
        yield ['progress'];
        yield ['progress:asc'];
        yield ['progress:desc'];
        yield ['started'];
        yield ['started:asc'];
        yield ['started:desc'];
        yield ['finished'];
        yield ['finished:asc'];
        yield ['finished:desc'];
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidSortOptionShouldReturnException()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())->method('get');

        $api->all(1, ['sort' => 'foobar']);
    }

    /**
     * @test
     */
    public function shouldShowImageAction()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/images/1/actions/2')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1, 2));
    }

    /**
     * @test
     */
    public function shouldChangeProtection()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/images/1/actions/change_protection')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->changeProtection(1, ['delete' => true]));
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\Images\Actions';
    }
}
