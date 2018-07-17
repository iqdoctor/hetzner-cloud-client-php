<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\Tests\Api\Servers;

use HetznerCloud\Tests\Api\TestCase;

/**
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ActionsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAllServerActions()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers/1/actions', ['page' => 2, 'per_page' => 5])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(1, ['page' => 2, 'per_page' => 5]));
    }

    /**
     * @test
     * @dataProvider getStatusProvider
     */
    public function shouldGetAllServerActionsByStatus($status)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers/1/actions', ['status' => $status])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(1, ['status' => $status]));
    }

    public function getStatusProvider()
    {
        yield ['running'];
        yield ['success'];
        yield ['error'];
    }

    /**
     * @test
     * @dataProvider getSortProvider
     */
    public function shouldGetAllServerActionsSorted($sort)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers/1/actions', ['sort' => $sort])
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->all(1, ['sort' => $sort]));
    }

    public function getSortProvider()
    {
        yield ['id'];
        yield ['id:asc'];
        yield ['id:desc'];
        yield ['command'];
        yield ['command:asc'];
        yield ['command:desc'];
        yield ['status'];
        yield ['status:asc'];
        yield ['status:desc'];
        yield ['progress'];
        yield ['progress:asc'];
        yield ['progress:desc'];
        yield ['started'];
        yield ['started:asc'];
        yield ['started:desc'];
        yield ['finished'];
        yield ['finished:asc'];
        yield ['finished:desc'];
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidSortOptionShouldReturnException()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())->method('get');

        $api->all(1, ['sort' => 'foobar']);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidStatusOptionShouldReturnException()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())->method('get');

        $api->all(1, ['status' => 'foobar']);
    }

    /**
     * @test
     */
    public function shouldShowServerAction()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/servers/1/actions/2')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->show(1, 2));
    }

    /**
     * @test
     */
    public function shouldPowerOnServer()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/poweron')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->powerOn(1));
    }

    /**
     * @test
     */
    public function shouldRebootServer()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/reboot')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->reboot(1));
    }

    /**
     * @test
     */
    public function shouldResetServer()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/reset')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->reset(1));
    }

    /**
     * @test
     */
    public function shouldShutdownServer()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/shutdown')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->shutdown(1));
    }

    /**
     * @test
     */
    public function shouldPowerOffServer()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/poweroff')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->powerOff(1));
    }

    /**
     * @test
     */
    public function shouldResetRootPassword()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/reset_password')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->resetPassword(1));
    }

    /**
     * @test
     * @dataProvider getRescueModeProvider
     */
    public function shouldEnableRescueMode($type, $ssh_keys)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/enable_rescue')
            ->will($this->returnValue($expectedArray));

        $params = [];
        $params['type'] = $type;
        if (!empty($params['ssh_keys'])) {
            $params['ssh_keys'] = $ssh_keys;
        }

        $this->assertEquals($expectedArray, $api->enableRescue(1, $params));
    }

    public function getRescueModeProvider()
    {
        yield ['linux64', null];
        yield ['linux32', null];
        yield ['freebsd64', null];
        yield ['linux64', ['ssh key']];
        yield ['linux32', ['ssh key']];
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     */
    public function shouldNotEnableRescueModeWithSshKeyOnFreebsd()
    {
        $api = $this->getApiMock();
        $api->expects($this->never())->method('post');

        $api->enableRescue(1, ['type' => 'freebsd64', 'ssh_keys' => ['ssh_key']]);
    }

    /**
     * @test
     */
    public function shouldDisableRescueMode()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/disable_rescue')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->disableRescue(1));
    }

    /**
     * @test
     */
    public function shouldCreateImageFromServerWithoutParams()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/create_image')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->createImage(1));
    }

    /**
     * @test
     * @dataProvider getTypeProvider
     */
    public function shouldCreateImageFromServerWithParams($type)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/create_image')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->createImage(1, ['description' => 'test', 'type' => $type]));
    }

    public function getTypeProvider()
    {
        yield ['snapshot'];
        yield ['backup'];
    }

    /**
     * @test
     */
    public function shouldRebuidServerFromImage()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/rebuild')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->rebuild(1, ['image' => 'test']));
    }

    /**
     * @test
     */
    public function shouldChangeType()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/change_type')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->changeType(1, ['server_type' => 'test', 'upgrade_disk' => false]));
    }

    /**
     * @test
     * @dataProvider getBackupWindowProvider
     */
    public function shoudlEnableBackup($backupWindow)
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/enable_backup')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->enableBackup(1, ['backup_window' => $backupWindow]));
    }

    public function getBackupWindowProvider()
    {
        yield ['22-02'];
        yield ['02-06'];
        yield ['06-10'];
        yield ['10-14'];
        yield ['14-18'];
        yield ['18-22'];
    }

    /**
     * @test
     */
    public function shouldDisableBackup()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/disable_backup')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->disableBackup(1));
    }

    /**
     * @test
     */
    public function shouldAttachIso()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/attach_iso')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->attachIso(1, ['iso' => 'test']));
    }

    /**
     * @test
     */
    public function shouldDetachIso()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/detach_iso')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->detachIso(1));
    }

    /**
     * @test
     */
    public function shouldChangeReverseDns()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/change_dns_ptr')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->changeDnsPtr(1, ['ip' => '1.1.1.1', 'dns_ptr' => 'test.test.de']));
    }

    /**
     * @test
     */
    public function shouldChangeProtection()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/change_protection')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->changeProtection(1, ['delete' => true, 'rebuild' => true]));
    }

    /**
     * @test
     */
    public function shouldRequestConsole()
    {
        $expectedArray = ['foobar'];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with('/servers/1/actions/request_console')
            ->will($this->returnValue($expectedArray));

        $this->assertEquals($expectedArray, $api->requestConsole(1));
    }

    protected function getApiClass()
    {
        return 'HetznerCloud\Api\Servers\Actions';
    }
}
