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
 * Server Types.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ServerTypes extends AbstractApi
{
    /**
     * Gets all server type objects.
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

        return $this->get('/server_types', $resolver->resolve($parameters));
    }

    /**
     * Gets a specific server type object.
     *
     * @param string $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id)
    {
        return $this->get('/server_types/' . $this->encodePath($id));
    }
}
