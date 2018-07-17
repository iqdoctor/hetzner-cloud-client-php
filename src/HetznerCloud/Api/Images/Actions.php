<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Api\Images;

use HetznerCloud\Api\AbstractApi;

/**
 * Images Actions
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Actions extends AbstractApi
{
    /**
     * Returns all action objects for an image. You can sort the results by using the sort URI parameter.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function all($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

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

        return $this->get('/images/' . $this->encodePath($id) . '/actions', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific action object for an image.
     *
     * @param integer $id
     * @param integer $action_id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id, $action_id)
    {
        return $this->get('/images/' . $this->encodePath($id) . '/actions/' . $this->encodePath($action_id));
    }

    /**
     * Changes the protection configuration of the image. Can only be used on snapshots.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function changeProtection($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('delete')
            ->setAllowedTypes('delete', 'bool');

        return $this->post('/images/' . $this->encodePath($id) . '/actions/change_protection', $resolver->resolve($parameters));
    }
}
