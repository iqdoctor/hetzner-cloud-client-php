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
class SshKeysTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllSshKeys()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/ssh_keys', ['page' => 2, 'per_page' => 5])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(['page' => 2, 'per_page' => 5]));
    }

    /**
     * @test
     */
    public function shouldShowSshKey()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/ssh_keys/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1));
    }

    /**
     * @test
     */
    public function shouldCreateSshKey()
    {
        $expectedArray = ['foobar'];
        $params = ['name' => 'ssh key', 'public_key' => 'key'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/ssh_keys')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->create($params));
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function shouldFailCreateSshKeyWithoutParams()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('post');

        $api->create([]);
    }

    /**
     * @test
     */
    public function shouldUpdateSshKey()
    {
        $expectedArray = ['foobar'];
        $params = ['name' => 'ssh key'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with('/ssh_keys/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->update(1, $params));
    }

    /**
     * @test
     */
    public function shouldRemoveSshKey()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('/ssh_keys/1')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->remove(1));
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\SshKeys';
    }
}
