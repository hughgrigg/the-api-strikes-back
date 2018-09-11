<?php

namespace DeathStarApi\Tests\Unit\Command;

use DeathStarApi\Tests\Unit\UnitTest;
use Generator;

/**
 * Unit test the console command classes.
 */
abstract class CommandTest extends UnitTest
{
    /** @var string[] */
    protected $input = [];

    /** @var string[] */
    protected $output = [];

    protected function tearDown()
    {
        parent::tearDown();

        $this->input = [];
        $this->output = [];
    }

    /**
     * @return Generator
     */
    protected function mockInput(): Generator
    {
        foreach ($this->input as $line) {
            yield $line;
        }
    }

    /**
     * @return Generator
     */
    protected function mockOutput(): Generator
    {
        while (true) {
            $this->output[] = trim(yield true);
        }
    }
}
