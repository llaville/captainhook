<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command\Placeholder;

use CaptainHook\App\Config\Plugin;
use CaptainHook\App\Console\IO\Mockery as IOMockery;
use CaptainHook\App\Config\Mockery as ConfigMockery;
use CaptainHook\App\Mockery as AppMockery;
use CaptainHook\App\Plugin\DummyPlugin;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    use IOMockery;
    use AppMockery;
    use ConfigMockery;

    public function testConfigValue(): void
    {
        $io     = $this->createIOMock();
        $repo   = $this->createRepositoryMock();
        $config = $this->createConfigMock();
        $config->expects($this->once())->method('getGitDirectory')->willReturn('./.git');

        $placeholder = new Config($io, $config, $repo);
        $gitDir      = $placeholder->replacement(['value-of' => 'git-directory']);

        $this->assertEquals('./.git', $gitDir);
    }

    public function testCustomConfigValue(): void
    {
        $io     = $this->createIOMock();
        $repo   = $this->createRepositoryMock();
        $config = $this->createConfigMock();
        $config->expects($this->once())->method('getCustomSettings')->willReturn(['foo' => 'bar']);

        $placeholder = new Config($io, $config, $repo);
        $replace     = $placeholder->replacement(['value-of' => 'custom>>foo']);

        $this->assertEquals('bar', $replace);
    }

    public function testCustomConfigValueNotFound(): void
    {
        $io     = $this->createIOMock();
        $repo   = $this->createRepositoryMock();
        $config = $this->createConfigMock();
        $config->expects($this->once())->method('getCustomSettings')->willReturn(['foo' => 'bar']);

        $placeholder = new Config($io, $config, $repo);
        $replace     = $placeholder->replacement(['value-of' => 'custom>>bar']);

        $this->assertEquals('', $replace);
    }

    public function testPluginConfigValue(): void
    {
        $io     = $this->createIOMock();
        $repo   = $this->createRepositoryMock();
        $config = $this->createConfigMock();
        $config->expects($this->once())->method('getPlugins')->willReturn(
            [
                new Plugin(DummyPlugin::class, ['foo' => 'bar']),
                new Plugin(DummyPlugin::class, ['fiz' => 'baz']),
            ]
        );

        $placeholder = new Config($io, $config, $repo);
        $replace     = $placeholder->replacement(['value-of' => 'plugin>>' . DummyPlugin::class . '.foo']);

        $this->assertEquals('bar', $replace);
    }

    public function testPluginConfigValueNotFound(): void
    {
        $io     = $this->createIOMock();
        $repo   = $this->createRepositoryMock();
        $config = $this->createConfigMock();
        $config->expects($this->once())->method('getPlugins')->willReturn(
            [
                new Plugin(DummyPlugin::class, ['foo' => 'bar']),
                new Plugin(DummyPlugin::class, ['fiz' => 'baz']),
            ]
        );

        $placeholder = new Config($io, $config, $repo);
        $replace     = $placeholder->replacement(['value-of' => 'plugin>>fiz.baz']);

        $this->assertEquals('', $replace);
    }

    public function testNoValueOf(): void
    {
        $io     = $this->createIOMock();
        $repo   = $this->createRepositoryMock();
        $config = $this->createConfigMock();

        $placeholder = new Config($io, $config, $repo);
        $gitDir      = $placeholder->replacement([]);

        $this->assertEquals('', $gitDir);
    }

    public function testInvalidConfigValue(): void
    {
        $io     = $this->createIOMock();
        $repo   = $this->createRepositoryMock();
        $config = $this->createConfigMock();

        $placeholder = new Config($io, $config, $repo);
        $gitDir      = $placeholder->replacement(['value-of' => 'includes']);

        $this->assertEquals('', $gitDir);
    }
}
