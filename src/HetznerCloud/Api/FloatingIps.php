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
 * Floating IPs help you to create highly available setups. You can assign a Floating IP to any server.
 * Floating IPs are billed on a monthly basis.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class FloatingIps extends AbstractApi
{
    /**
     * @return FloatingIps\Actions
     */
    public function actions()
    {
        return new FloatingIps\Actions($this->client);
    }

    /**
     * Returns all floating ip objects.
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

        return $this->get('/floating_ips', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific floating ip object.
     *
     * @param $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id)
    {
        return $this->get('/floating_ips/' . $this->encodePath($id));
    }

    /**
     * Creates a new Floating IP assigned to a server. If you want to create a Floating IP that is not bound to a
     * server, you need to provide the home_location key instead of server. This can be either the ID or the name of
     * the location this IP shall be created in. Note that a Floating IP can be assigned to a server in any location
     * later on. For optimal routing it is advised to use the Floating IP in the same Location it was created in.
     *
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function create(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setRequired('type')
            ->setAllowedValues('type', ['ipv4', 'ipv6']);

        $resolver->setDefined('server')
            ->setAllowedTypes('server', 'int')
            ->setAllowedValues(
                'server',
                function ($value) {
                    return $value > 0;
                }
            );

        //  home_location only optional if server argument is passed.
        if (!empty($parameters['server'])) {
            $resolver->setDefined('home_location');
        } else {
            $resolver->setRequired('home_location');
        }
        $resolver->setAllowedTypes('home_location', 'string');

        $resolver->setDefined('description')
            ->setAllowedTypes('description', 'string');

        return $this->post('/floating_ips', $resolver->resolve($parameters));
    }

    /**
     * Changes the description of a Floating IP.
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

        $resolver->setDefined('description')
            ->setAllowedTypes('description', 'string');

        return $this->put('/floating_ips/' . $this->encodePath($id), $resolver->resolve($parameters));
    }

    /**
     * Deletes a Floating IP. If it is currently assigned to a server it will automatically get unassigned.
     *
     * @param $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function remove($id)
    {
        return $this->delete('/floating_ips/' . $this->encodePath($id));
    }
}
