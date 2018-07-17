<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Api;

use HetznerCloud\Client;
use HetznerCloud\HttpClient\Message\ResponseMediator;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * AbstractApi.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * The client
     *
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    protected function get($path, array $parameters = [], array $requestHeaders = [])
    {
        if (count($parameters) > 0) {
            $path .= '?' . http_build_query($parameters);
        }

        $response = $this->client->getHttpClient()->get($path, $requestHeaders);

        return ResponseMediator::getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    protected function post($path, array $parameters = [], array $requestHeaders = [])
    {
        $response = $this->client->getHttpClient()->post(
            $path,
            $requestHeaders,
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    protected function put($path, array $parameters = [], array $requestHeaders = [])
    {
        $response = $this->client->getHttpClient()->put(
            $path,
            $requestHeaders,
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     *
     * @return array|string
     * @throws \Http\Client\Exception
     */
    protected function delete($path, array $parameters = [], array $requestHeaders = [])
    {
        $response = $this->client->getHttpClient()->delete(
            $path,
            $requestHeaders,
            $this->createJsonBody($parameters)
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * @return OptionsResolver
     */
    protected function createOptionsResolver()
    {
        $resolver = new OptionsResolver();

        $resolver->setDefined('page')
            ->setAllowedTypes('page', 'int')
            ->setAllowedValues(
                'page',
                function ($value) {
                    return $value > 0;
                }
            );

        $resolver->setDefined('per_page')
            ->setAllowedTypes('per_page', 'int')
            ->setAllowedValues(
                'per_page',
                function ($value) {
                    return $value > 0 && $value <= 50;
                }
            );

        return $resolver;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function encodePath($path)
    {
        return str_replace('.', '%2E', rawurlencode($path));
    }

    /**
     * @param array $parameters
     *
     * @return null|string
     */
    protected function createJsonBody(array $parameters)
    {
        return (count($parameters) === 0) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }
}
