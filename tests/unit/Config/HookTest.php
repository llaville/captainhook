<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Config;

use PHPUnit\Framework\TestCase;

class HookTest extends TestCase
{
    public function testInitiallyItIsDisabled(): void
    {
        $hook   = new Hook('pre-commit');
        $config = $hook->getJsonData();

        $this->assertFalse($hook->isEnabled());
        $this->assertFalse($config['enabled']);
    }

    public function testEnabledStateIsExportedToJsonCorrectly(): void
    {
        $hook   = new Hook('pre-commit');
        $hook->setEnabled(true);
        $config = $hook->getJsonData();

        $this->assertTrue($hook->isEnabled());
        $this->assertTrue($config['enabled']);
    }

    public function testInitiallyItHasNoActions(): void
    {
        $hook   = new Hook('pre-commit');
        $config = $hook->getJsonData();

        $this->assertCount(0, $hook->getActions());
        $this->assertCount(0, $config['actions']);
    }

    public function testAllowsAddingActions(): void
    {
        $hook = new Hook('pre-commit');
        $hook->addAction(new Action('\\Foo\\Bar'));
        $config = $hook->getJsonData();

        $this->assertCount(1, $hook->getActions());
        $this->assertCount(1, $config['actions']);
    }

    public function testDeterminesIfItHasActionsThatWereNotIncluded(): void
    {
        $localAction    = new Action('\\Foo\\Bar');
        $includedAction = new Action('\\Foo\\Bar');
        $includedAction->markIncluded();

        $hook = new Hook('pre-commit');
        $hook->addAction($localAction, $includedAction);
        $this->assertTrue($hook->hasLocalActions());
    }

    public function testDeterminesItCantHaveLocalActionsIfItDoesntHaveAnyActions(): void
    {
        $hook = new Hook('pre-commit');
        $this->assertFalse($hook->hasLocalActions());
    }

    public function testDeterminesIfItHasIncludedActionsOnly(): void
    {
        $includedAction = new Action('\\Foo\\Bar');
        $includedAction->markIncluded();

        $hook   = new Hook('pre-commit');
        $hook->addAction($includedAction);
        $this->assertFalse($hook->hasLocalActions());
    }

    public function testAllowsAddingOfMultipleActions(): void
    {
        $hook   = new Hook('pre-commit');
        $hook->addAction(new Action('\\Foo\\Bar'), new Action('\\Foo\\Bar'));
        $config = $hook->getJsonData();

        $this->assertCount(2, $hook->getActions());
        $this->assertCount(2, $config['actions']);
    }
}
