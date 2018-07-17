<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Tests\HttpClient\Message;

use GuzzleHttp\Psr7\Response;
use HetznerCloud\HttpClient\Message\ResponseMediator;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ResponseMediatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getContent()
    {
        $body = ['foo' => 'bar'];
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode($body))
        );

        $this->assertEquals('bar', ResponseMediator::getContent($response));
    }

    /**
     * @test
     */
    public function getContentNotJson()
    {
        $body = 'foobar';
        $response = new Response(
            200,
            [],
            \GuzzleHttp\Psr7\stream_for($body)
        );

        $this->assertEquals($body, ResponseMediator::getContent($response));
    }

    /**
     * @test
     */
    public function getContentInvalidJson()
    {
        $body = 'foobar';
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for($body)
        );

        $this->assertEquals($body, ResponseMediator::getContent($response));
    }

    /**
     * @test
     */
    public function getPagination()
    {
        $header = <<<TEXT
<https://api.hetzner.cloud/first>; rel=first,
<https://api.hetzner.cloud/next>; rel=next,
<https://api.hetzner.cloud/prev>; rel=prev,
<https://api.hetzner.cloud/last>; rel=last,
TEXT;

        $pagination = [
            'first' => 'https://api.hetzner.cloud/first',
            'next' => 'https://api.hetzner.cloud/next',
            'prev' => 'https://api.hetzner.cloud/prev',
            'last' => 'https://api.hetzner.cloud/last',
        ];

        $response = new Response(200, ['link' => $header]);
        $result = ResponseMediator::getPagination($response);

        $this->assertEquals($pagination, $result);
    }
}
