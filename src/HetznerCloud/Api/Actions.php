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

/**
 * Actions show the results and progress of asynchronous requests to the API.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Actions extends AbstractApi
{
    /**
     * Returns all action objects. You can select specific actions only and sort the results by using URI parameters.
     *
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('status')
            ->setAllowedTypes('status', 'string')
            ->setAllowedValues(
                'status',
                [
                    'running',
                    'success',
                    'error',
                ]
            );

        $resolver->setDefined('sort')
            ->setAllowedTypes('sort', 'string')
            ->setAllowedValues(
                'sort',
                [
                    'id',
                    'id:asc',
                    'id:desc',
                    'command',
                    'command:asc',
                    'command:desc',
                    'status',
                    'status:asc',
                    'status:desc',
                    'progress',
                    'progress:asc',
                    'progress:desc',
                    'started',
                    'started:asc',
                    'started:desc',
                    'finished',
                    'finished:asc',
                    'finished:desc',
                ]
            );

        return $this->get('/actions', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific action object.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id)
    {
        return $this->get('/actions/' . $this->encodePath($id));
    }
}
