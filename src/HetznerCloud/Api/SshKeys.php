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
 * SSH keys are public keys you provide to the cloud system. They can be injected into servers at creation time.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class SshKeys extends AbstractApi
{
    /**
     * Returns all SSH key objects.
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

        $resolver->setDefined('name')
            ->setAllowedTypes('name', 'string');

        $resolver->setDefined('fingerprint')
            ->setAllowedTypes('fingerprint', 'string');

        return $this->get('/ssh_keys', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific SSH key object.
     *
     * @param $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id)
    {
        return $this->get('/ssh_keys/' . $this->encodePath($id));
    }

    /**
     * Creates a new SSH key with the given name and public_key. Once an SSH key is created, it can be used in other
     * calls such as creating servers.
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

        $resolver->setRequired('name')
            ->setAllowedTypes('name', 'string');

        $resolver->setRequired('public_key')
            ->setAllowedTypes('public_key', 'string');

        return $this->post('/ssh_keys', $resolver->resolve($parameters));
    }

    /**
     * Changes the name of an SSH key.
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

        $resolver->setDefined('name')
            ->setAllowedTypes('name', 'string');

        return $this->put('/ssh_keys/' . $this->encodePath($id), $resolver->resolve($parameters));
    }

    /**
     * Deletes an SSH key. It cannot be used anymore.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function remove($id)
    {
        return $this->delete('/ssh_keys/' . $this->encodePath($id));
    }
}
