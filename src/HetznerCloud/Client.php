<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud;

use HetznerCloud\Api;
use HetznerCloud\HttpClient\Builder;
use HetznerCloud\HttpClient\Plugin\ApiVersion;
use HetznerCloud\HttpClient\Plugin\Authentication;
use HetznerCloud\HttpClient\Plugin\ExceptionThrower;
use HetznerCloud\HttpClient\Plugin\History;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\HttpClient;
use Http\Discovery\UriFactoryDiscovery;

/**
 * Simple API wrapper for the (awesome) Hetzner Cloud
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Client
{
    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @var History
     */
    private $responseHistory;

    /**
     * @param Builder|null $httpClientBuilder
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->responseHistory = new History();
        $this->httpClientBuilder = $builder = $httpClientBuilder ?: new Builder();

        $builder->addPlugin(new ExceptionThrower());
        $builder->addPlugin(new ApiVersion());
        $builder->addPlugin(new Plugin\HistoryPlugin($this->responseHistory));
        $builder->addPlugin(
            new Plugin\HeaderDefaultsPlugin(
                [
                    'User-Agent' => 'hetzner-cloud-client-php (https://github.com/arkste/hetzner-cloud-client-php)',
                ]
            )
        );
        $builder->addPlugin(
            new Plugin\AddHostPlugin(
                UriFactoryDiscovery::find()->createUri(
                    'https://api.hetzner.cloud'
                )
            )
        );
    }

    /**
     * @return Client
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(HttpClient $httpClient)
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function authenticate($token)
    {
        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($token));

        return $this;
    }

    /**
     * @return Api\Actions
     */
    public function actions()
    {
        return new Api\Actions($this);
    }

    /**
     * @return Api\Datacenters
     */
    public function datacenters()
    {
        return new Api\Datacenters($this);
    }

    /**
     * @return Api\FloatingIps
     */
    public function floatingIps()
    {
        return new Api\FloatingIps($this);
    }

    /**
     * @return Api\Images
     */
    public function images()
    {
        return new Api\Images($this);
    }

    /**
     * @return Api\Isos
     */
    public function isos()
    {
        return new Api\Isos($this);
    }

    /**
     * @return Api\Locations
     */
    public function locations()
    {
        return new Api\Locations($this);
    }

    /**
     * @return Api\Pricing
     */
    public function pricing()
    {
        return new Api\Pricing($this);
    }

    /**
     * @return Api\Servers
     */
    public function servers()
    {
        return new Api\Servers($this);
    }

    /**
     * @return Api\ServerTypes
     */
    public function serverTypes()
    {
        return new Api\ServerTypes($this);
    }

    /**
     * @return Api\SshKeys
     */
    public function sshKeys()
    {
        return new Api\SshKeys($this);
    }

    /**
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function getLastResponse()
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * @return HttpMethodsClient
     */
    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * @return Builder
     */
    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }

    /**
     * @return History
     */
    public function getResponseHistory()
    {
        return $this->responseHistory;
    }
}
