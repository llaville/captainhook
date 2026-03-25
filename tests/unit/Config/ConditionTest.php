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

class ConditionTest extends TestCase
{
    public function testGetExec(): void
    {
        $config = new Condition('\\Foo\\Bar');

        $this->assertEquals('\\Foo\\Bar', $config->getExec());
    }

    public function testGetEmptyArgs(): void
    {
        $config = new Condition('\\Foo\\Bar');

        $this->assertEquals([], $config->getArgs());
    }

    public function testCanIdentifyLogicConditions(): void
    {
        $config1 = new Condition('OR');
        $this->assertTrue($config1->isLogicCondition());

        $config2 = new Condition('AND');
        $this->assertTrue($config2->isLogicCondition());

        $config3 = new Condition('\\Foo\\Bar');
        $this->assertFalse($config3->isLogicCondition());
    }
}
