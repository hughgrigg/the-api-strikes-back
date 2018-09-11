<?php

namespace DeathStarApi\Tests\Unit\Command;

use DeathStarApi\Command\TranslateDroidSpeak;

class TranslateDroidSpeakTest extends CommandTest
{
    /** @var TranslateDroidSpeak */
    private $translateDroidSpeakCommand;

    protected function setUp()
    {
        parent::setUp();

        $this->translateDroidSpeakCommand = new TranslateDroidSpeak(
            $this->container,
            [], // argv
            $this->mockInput(),
            $this->mockOutput()
        );
    }

    /**
     * Should see input DroidSpeak as Galactic Basic output.
     */
    public function testTranslatesInputToGalacticBasic(): void
    {
        // Given we have some DroidSpeak lines as input;
        $cellDroidSpeak = <<<'CELL'
01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111
CELL;
        $blockDroidSpeak = <<<'BLOCK'
 01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111
 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000
 01000001 01000001 00101101 00110010 00110011 00101100
BLOCK;

        $this->input = [$cellDroidSpeak, $blockDroidSpeak];

        // When the translate DroidSpeak command is run;
        $this->translateDroidSpeakCommand->run();

        // Then we should get output in Galactic Basic.
        self::assertContains('Cell 2187', $this->output, print_r($this->output, true));
        self::assertContains('Detention Block AA-23,', $this->output, print_r($this->output, true));
    }
}
