<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Api\Servers;

use HetznerCloud\Api\AbstractApi;

/**
 * Servers Actions
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class Actions extends AbstractApi
{
    /**
     * Returns all action objects for a server.
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

        $resolver->setDefined('status')
            ->setAllowedTypes('status', 'string')
            ->setAllowedValues('status', ['running', 'success', 'error']);

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

        return $this->get('/servers/' . $this->encodePath($id) . '/actions', $resolver->resolve($parameters));
    }

    /**
     * Returns a specific action object for a Server.
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
        return $this->get('/servers/' . $this->encodePath($id) . '/actions/' . $this->encodePath($action_id));
    }

    /**
     * Starts a server by turning its power on.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function powerOn($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/poweron');
    }

    /**
     * Reboots a server gracefully by sending an ACPI request. The server operating system must support ACPI and react
     * to the request, otherwise the server will not reboot.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function reboot($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/reboot');
    }

    /**
     * Cuts power to a server and starts it again. This forcefully stops it without giving the server operating system
     * time to gracefully stop. This may lead to data loss, it’s equivalent to pulling the power cord and plugging it
     * in again. Reset should only be used when reboot does not work.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function reset($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/reset');
    }

    /**
     * Shuts down a server gracefully by sending an ACPI shutdown request. The server operating system must support
     * ACPI and react to the request, otherwise the server will not shut down.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function shutdown($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/shutdown');
    }

    /**
     * Cuts power to the server. This forcefully stops it without giving the server operating system time to gracefully
     * stop. May lead to data loss, equivalent to pulling the power cord. Power off should only be used when shutdown
     * does not work.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function powerOff($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/poweroff');
    }

    /**
     * Resets the root password. Only works for Linux systems that are running the qemu guest agent. Server must be
     * powered on (state on) in order for this operation to succeed.
     * This will generate a new password for this server and return it.
     * If this does not succeed you can use the rescue system to netboot the server and manually change your server
     * password by hand.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function resetPassword($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/reset_password');
    }

    /**
     * Enable the Hetzner Rescue System for this server. The next time a Server with enabled rescue mode boots it will
     * start a special minimal Linux distribution designed for repair and reinstall.
     * In case a server cannot boot on its own you can use this to access a server’s disks.
     * Rescue Mode is automatically disabled when you first boot into it or if you do not use it for 60 minutes.
     * Enabling rescue mode will not reboot your server — you will have to do this yourself.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function enableRescue($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('type')
            ->setAllowedTypes('type', 'string')
            ->setAllowedValues('type', ['linux64', 'linux32', 'freebsd64']);

        if (!empty($parameters['type']) && in_array($parameters['type'], ['linux64', 'linux32'])) {
            $resolver->setDefined('ssh_keys')
                ->setAllowedTypes('ssh_keys', 'array');
        }

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/enable_rescue', $resolver->resolve($parameters));
    }

    /**
     * Disables the Hetzner Rescue System for a server. This makes a server start from its disks on next reboot.
     * Rescue Mode is automatically disabled when you first boot into it or if you do not use it for 60 minutes.
     * Disabling rescue mode will not reboot your server — you will have to do this yourself.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function disableRescue($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/disable_rescue');
    }

    /**
     * Creates an image (snapshot) from a server by copying the contents of its disks. This creates a snapshot of the
     * current state of the disk and copies it into an image. If the server is currently running you must make sure that
     * its disk content is consistent. Otherwise, the created image may not be readable.
     * To make sure disk content is consistent, we recommend to shut down the server prior to creating an image.
     * You can either create a backup image that is bound to the server and therefore will be deleted when the server
     * is deleted, or you can create an snapshot image which is completely independent of the server it was created from
     * and will survive server deletion. Backup images are only available when the backup option is enabled for the Server.
     * Snapshot images are billed on a per GB basis.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function createImage($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('description')
            ->setAllowedTypes('description', 'string');

        $resolver->setDefined('type')
            ->setDefault('type', 'snapshot')
            ->setAllowedTypes('type', 'string')
            ->setAllowedValues('type', ['snapshot', 'backup']);

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/create_image', $resolver->resolve($parameters));
    }

    /**
     * Rebuilds a server overwriting its disk with the content of an image, thereby **destroying all data** on the target server
     * The image can either be one you have created earlier (backup or snapshot image) or it can be a completely fresh
     * system image provided by us. You can get a list of all available images with GET /images.
     * Your server will automatically be powered off before the rebuild command executes.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function rebuild($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setRequired('image')
            ->setAllowedTypes('image', 'string');

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/rebuild', $resolver->resolve($parameters));
    }

    /**
     * Changes the type (Cores, RAM and disk sizes) of a server.
     * Server must be powered off for this command to succeed.
     * This copies the content of its disk, and starts it again.
     * You can only migrate to server types with the same storage_type and equal or bigger disks. Shrinking disks is not possible as it might destroy data.
     * If the disk gets upgraded, the server type can not be downgraded any more. If you plan to downgrade the server type, set upgrade_disk to false.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function changeType($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('upgrade_disk')
            ->setAllowedTypes('upgrade_disk', 'bool')
            ->setAllowedValues('upgrade_disk', [true, false]);

        $resolver->setRequired('server_type')
            ->setAllowedTypes('server_type', 'string');

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/change_type', $resolver->resolve($parameters));
    }

    /**
     * Enables and configures the automatic daily backup option for the server. Enabling automatic backups will increase
     * the price of the server by 20%. In return, you will get seven slots where images of type backup can be stored.
     * Backups are automatically created daily.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function enableBackup($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('backup_window')
            ->setAllowedTypes('backup_window', 'string')
            ->setAllowedValues(
                'backup_window',
                [
                    '22-02',
                    '02-06',
                    '06-10',
                    '10-14',
                    '14-18',
                    '18-22',
                ]
            );

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/enable_backup', $resolver->resolve($parameters));
    }

    /**
     * Disables the automatic backup option and deletes all existing Backups for a Server. No more additional charges for backups will be made.
     * Caution: This immediately removes all existing backups for the server!
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function disableBackup($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/disable_backup');
    }

    /**
     * Attaches an ISO to a server. The Server will immediately see it as a new disk. An already attached ISO will
     * automatically be detached before the new ISO is attached.
     * Servers with attached ISOs have a modified boot order: They will try to boot from the ISO first before falling
     * back to hard disk.
     *
     * @param integer $id
     * @param array $parameters
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function attachIso($id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setRequired('iso')
            ->setAllowedTypes('iso', 'string');

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/attach_iso', $resolver->resolve($parameters));
    }

    /**
     * Detaches an ISO from a server. In case no ISO image is attached to the server, the status of the returned action is immediately set to success.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function detachIso($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/detach_iso');
    }

    /**
     * Changes the hostname that will appear when getting the hostname belonging to the primary IPs (ipv4 and ipv6) of this server.
     * Floating IPs assigned to the server are not affected by this.
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

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/change_dns_ptr', $resolver->resolve($parameters));
    }

    /**
     * Changes the protection configuration of the server.
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

        $resolver->setDefined('rebuild')
            ->setAllowedTypes('rebuild', 'bool');

        return $this->post('/servers/' . $this->encodePath($id) . '/actions/change_protection', $resolver->resolve($parameters));
    }

    /**
     * Requests credentials for remote access via vnc over websocket to keyboard, monitor, and mouse for a server.
     * The provided url is valid for 1 minute, after this period a new url needs to be created to connect to the server.
     * How long the connection is open after the initial connect is not subject to this timeout.
     *
     * @param integer $id
     *
     * @return array|string
     *
     * @throws \Http\Client\Exception
     */
    public function requestConsole($id)
    {
        return $this->post('/servers/' . $this->encodePath($id) . '/actions/request_console');
    }
}
