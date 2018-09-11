<?php

namespace DeathStarApi\Tests\Unit\Command;

use DeathStarApi\Command\GetPrisonerLocation;
use DeathStarApi\Remote\PrisonerLocation;

class GetPrisonerLocationTest extends CommandTest
{
    /** @var GetPrisonerLocation */
    private $prisonerLocationCommand;

    protected function setUp()
    {
        parent::setUp();

        $this->prisonerLocationCommand = new GetPrisonerLocation(
            $this->container,
            ['', '', 'leia'], // argv
            $this->mockInput(),
            $this->mockOutput()
        );
    }

    /**
     * Should see the location of the prisoner in DroidSpeak.
     */
    public function testPrisonerLocationDroidSpeak(): void
    {
        // Given the Death Star API will respond to a prisoner location request
        // with a DroidSpeak location.
        $cellDroidSpeak = <<<'CELL'
01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111
CELL;
        $blockDroidSpeak = <<<'BLOCK'
01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110
00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001
00101101 00110010 00110011 00101100
BLOCK;
        $this->deathStarApi->expects($this->any())
            ->method('getPrisonerLocation')
            ->with('leia')
            ->willReturn(
                new PrisonerLocation(
                    $cellDroidSpeak,
                    $blockDroidSpeak
                )
            );

        // When the get prisoner location command is run;
        $this->prisonerLocationCommand->run();

        // Then we should see that the reactor exhaust was destroyed.
        self::assertContains($cellDroidSpeak, $this->output);
        self::assertContains($blockDroidSpeak, $this->output);
    }
}
