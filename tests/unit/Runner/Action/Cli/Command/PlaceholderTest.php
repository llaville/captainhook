<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command;

use PHPUnit\Framework\TestCase;

class PlaceholderTest extends TestCase
{
    public function testPlaceholderIsParsedCorrectly(): void
    {
        $placeholder = new Placeholder('STAGED_FILES|of-type:php');

        $this->assertEquals('STAGED_FILES|of-type:php', $placeholder->key());
        $this->assertEquals('staged_files', $placeholder->name());
        $this->assertEquals('php', $placeholder->option('of-type'));
    }

    public function testPlaceholderIsCacheacbleByDefault(): void
    {
        $placeholder = new Placeholder('STAGED_FILES|of-type:php');
        $this->assertTrue($placeholder->isCacheable());
    }

    public function testPlaceholderCanBeMarkedAsNotCacheable(): void
    {
        $placeholder = new Placeholder('STAGED_FILES|of-type:php|cache:1');
        $this->assertTrue($placeholder->isCacheable());

        $placeholder = new Placeholder('STAGED_FILES|of-type:php|cache:false');
        $this->assertFalse($placeholder->isCacheable());

        $placeholder = new Placeholder('STAGED_FILES|of-type:php|cache:0');
        $this->assertFalse($placeholder->isCacheable());

        $placeholder = new Placeholder('STAGED_FILES|of-type:php|cache:no');
        $this->assertFalse($placeholder->isCacheable());
    }

    public function testPlaceholderCanHaveMultipleOptions(): void
    {
        $placeholder = new Placeholder('STAGED_FILES|of-type:php|separated-by:,');
        $this->assertEquals('php', $placeholder->option('of-type'));
        $this->assertEquals(',', $placeholder->option('separated-by'));
    }
}
