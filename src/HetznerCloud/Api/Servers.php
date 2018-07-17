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
 * Servers are virtual machines that can be provisioned.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Servers extends AbstractApi
{
    /**
     * @return Servers\Actions
     */
    public function actions()
    {
        return new Servers\Actions($this->client);
    }

    /**
     * Returns all existing server objects.
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

        $resolver->setDefined('name');

        return $this->get('/servers', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific server object. The server must exist inside the project.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id)
    {
        return $this->get('/servers/' . $this->encodePath($id));
    }

    /**
     * Creates a new server. Returns preliminary information about the server as well as an action that covers
     * progress of creation.
     *
     * @param array $parameters
     *
     * @return array|string,
     *
     * @throws \Http\Client\Exception
     */
    public function create(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setRequired('name')
            ->setAllowedTypes('name', 'string');

        $resolver->setRequired('server_type')
            ->setAllowedTypes('server_type', 'string');

        $resolver->setDefined('start_after_create')
            ->setDefault('start_after_create', true)
            ->setAllowedTypes('start_after_create', 'bool')
            ->setAllowedValues('start_after_create', [true, false]);

        $resolver->setRequired('image');

        $resolver->setDefined('ssh_keys')
            ->setAllowedTypes('ssh_keys', 'array');

        $resolver->setDefined('user_data');

        $resolver->setDefined('location');

        $resolver->setDefined('datacenter');

        return $this->post('/servers', $resolver->resolve($parameters));
    }

    /**
     * Changes the name of a server.
     * Please note that server names must be unique per project and valid hostnames as per RFC 1123 (i.e. may only
     * contain letters, digits, periods, and dashes).
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function update($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('name');

        return $this->put('/servers/' . $this->encodePath($id), $resolver->resolve($parameters));
    }

    /**
     * Deletes a server. This immediately removes the server from your account, and it is no longer accessible.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function remove($id)
    {
        return $this->delete('/servers/' . $this->encodePath($id));
    }

    /**
     * Get Metrics for specified server.
     * You must specify the type of metric to get: "cpu", "disk" or "network". You can also specify more than one type
     * by comma separation, e.g. "cpu,disk".
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function metrics($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setRequired('type')
            ->setAllowedValues(
                'type',
                [
                    'cpu',
                    'disk',
                    'network',
                    'cpu,disk',
                    'cpu,network',
                    'disk,network',
                    'cpu,disk,network',
                ]
            );

        $resolver->setRequired('start')
            ->setAllowedTypes('start', 'DateTime');

        $resolver->setRequired('end')
            ->setAllowedTypes('end', 'DateTime');

        $resolver->setDefined('step')
            ->setAllowedTypes('step', 'int')
            ->setAllowedValues(
                'step',
                function ($value) {
                    return $value > 0;
                }
            );

        return $this->get('/servers/' . $this->encodePath($id) . '/metrics', $resolver->resolve($parameters));
    }
}
