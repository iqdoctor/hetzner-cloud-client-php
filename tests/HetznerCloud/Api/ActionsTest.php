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
class ActionsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllActions()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/actions', ['page' => 2, 'per_page' => 5])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['page' => 2, 'per_page' => 5]));
    }

    /**
     * @test
     */
    public function shouldShowAction()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/actions/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1));
    }

    /**
     * @test
     * @dataProvider getSortProvider
     */
    public function shouldGetAllActionsSorted($sort)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/actions', ['sort' => $sort])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['sort' => $sort]));
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
     * @dataProvider getStatusProvider
     */
    public function shouldGetAllActionsByStatus($status)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/actions', ['status' => $status])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['status' => $status]));
    }

    public function getStatusProvider()
    {
        yield ['running'];
        yield ['success'];
        yield ['error'];
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidSortOptionShouldReturnException()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())->method('get');

        $api->all(['sort' => 'foobar']);
    }


    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidStatusOptionShouldReturnException()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())->method('get');

        $api->all(['status' => 'foobar']);
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\Actions';
    }
}
