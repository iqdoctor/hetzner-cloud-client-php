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
 * Isos.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Isos extends AbstractApi
{
    /**
     * Returns all available iso objects.
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

        return $this->get('/isos', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific iso object.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id)
    {
        return $this->get('/isos/' . $this->encodePath($id));
    }
}
