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
 * Images are blueprints for your VM disks. They can be of different types:
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Images extends AbstractApi
{
    /**
     * @return Images\Actions
     */
    public function actions()
    {
        return new Images\Actions($this->client);
    }

    /**
     * Returns all image objects. You can select specific image types only and sort the results by using URI parameters.
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

        $resolver->setDefined('type')
            ->setAllowedValues('type', ['system', 'snapshot', 'backup']);

        return $this->get('/images', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific image object.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function show($id)
    {
        return $this->get('/images/' . $this->encodePath($id));
    }

    /**
     * Updates the Image. You may change the description or convert a Backup image to a Snapshot Image. Only images of
     * type snapshot and backup can be updated.
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

        $resolver->setDefined('type')
            ->setAllowedTypes('type', 'string');

        return $this->put('/images/' . $this->encodePath($id), $resolver->resolve($parameters));
    }

    /**
     * Deletes an Image. Only images of type snapshot and backup can be deleted.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function remove($id)
    {
        return $this->delete('/images/' . $this->encodePath($id));
    }
}
