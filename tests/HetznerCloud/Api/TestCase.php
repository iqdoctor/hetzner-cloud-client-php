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
 * TestCase.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return string
     */
    abstract protected function getApiClass();

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getApiMock()
    {
        $httpClient = $this->getMockBuilder(\Http\Client\HttpClient::class)
            ->setMethods(['sendRequest'])
            ->getMock();
        $httpClient
            ->expects($this->any())
            ->method('sendRequest');

        $client = \HetznerCloud\Client::createWithHttpClient($httpClient);

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['get', 'post', 'delete', 'put'])
            ->setConstructorArgs([$client])
            ->getMock();
    }
}
