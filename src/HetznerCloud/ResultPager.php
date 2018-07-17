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

use HetznerCloud\Api\ApiInterface;
use HetznerCloud\HttpClient\Message\ResponseMediator;

/**
 * Pager class for supporting pagination in HetznerCloud classes
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ResultPager implements ResultPagerInterface
{
    /**
     * @var \HetznerCloud\Client client
     */
    protected $client;

    /**
     * The HetznerCloud client to use for pagination. This must be the same
     * instance that you got the Api instance from, i.e.:
     *
     * $client = new \HetznerCloud\Client();
     * $api = $client->api('someApi');
     * $pager = new \HetznerCloud\ResultPager($client);
     *
     * @param \HetznerCloud\Client $client
     *
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(ApiInterface $api, $method, array $parameters = [])
    {
        return call_user_func_array([$api, $method], $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll(ApiInterface $api, $method, array $parameters = [])
    {
        $result = call_user_func_array([$api, $method], $parameters);

        while ($this->hasNext()) {
            $result = array_merge($result, $this->fetchNext());
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext()
    {
        return $this->has('next');
    }

    /**
     * {@inheritdoc}
     */
    public function fetchNext()
    {
        return $this->get('next');
    }

    /**
     * {@inheritdoc}
     */
    public function hasPrevious()
    {
        return $this->has('prev');
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPrevious()
    {
        return $this->get('prev');
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFirst()
    {
        return $this->get('first');
    }

    /**
     * {@inheritdoc}
     */
    public function fetchLast()
    {
        return $this->get('last');
    }

    /**
     * {@inheritdoc}
     */
    protected function has($key)
    {
        $lastResponse = $this->client->getResponseHistory()->getLastResponse();
        if ($lastResponse == null) {
            return false;
        }

        $pagination = ResponseMediator::getPagination($lastResponse);
        if ($pagination == null) {
            return false;
        }

        return isset($pagination[$key]);
    }

    /**
     * {@inheritdoc}
     */
    protected function get($key)
    {
        if (!$this->has($key)) {
            return [];
        }

        $pagination = ResponseMediator::getPagination($this->client->getResponseHistory()->getLastResponse());

        return ResponseMediator::getContent($this->client->getHttpClient()->get($pagination[$key]));
    }
}
