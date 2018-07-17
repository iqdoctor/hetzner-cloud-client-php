<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Api\FloatingIps;

use HetznerCloud\Api\AbstractApi;

/**
 * Floating IPs Actions.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Actions extends AbstractApi
{
    /**
     * Returns all action objects for a Floating IP. You can sort the results by using the sort URI parameter.
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

        return $this->get('/floating_ips/' . $this->encodePath($id) . '/actions', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific action object for a Floating IP.
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
        return $this->get('/floating_ips/' . $this->encodePath($id) . '/actions/' . $this->encodePath($action_id));
    }

    /**
     * Assigns a Floating IP to a server.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function assign($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setRequired('server')
            ->setAllowedTypes('server', 'int')
            ->setAllowedValues(
                'server',
                function ($value) {
                    return $value > 0;
                }
            );

        return $this->post('/floating_ips/' . $this->encodePath($id) . '/actions/assign', $resolver->resolve($parameters));
    }

    /**
     * Unassigns a Floating IP, resulting in it being unreachable. You may assign it to a server again at a later time.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function unassign($id)
    {
        return $this->post('/floating_ips/' . $this->encodePath($id) . '/actions/unassign');
    }

    /**
     * Changes the hostname that will appear when getting the hostname belonging to this Floating IP.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function changeDnsPtr($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setRequired('ip')
            ->setAllowedTypes('ip', 'string');

        $resolver->setRequired('dns_ptr')
            ->setAllowedTypes('dns_ptr', ['string', 'null']);

        return $this->post('/floating_ips/' . $this->encodePath($id) . '/actions/change_dns_ptr', $resolver->resolve($parameters));
    }

    /**
     * Changes the protection configuration of the Floating IP.
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

        return $this->post('/floating_ips/' . $this->encodePath($id) . '/actions/change_protection', $resolver->resolve($parameters));
    }
}
