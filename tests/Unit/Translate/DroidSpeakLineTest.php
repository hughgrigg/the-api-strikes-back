<?php

namespace DeathStarApi\Tests\Unit\Translate;

use DeathStarApi\Tests\Unit\UnitTest;
use DeathStarApi\Translate\DroidSpeakLine;

class DroidSpeakLineTest extends UnitTest
{
    /**
     * @dataProvider droidSpeakProvider
     *
     * @param string $droidSpeak
     * @param string $expectedGalacticBasic
     */
    public function testTranslateToGalacticBasic(
        string $droidSpeak,
        string $expectedGalacticBasic
    ): void {
        // Given we have a line of DroidSpeak;
        $droidSpeak = new DroidSpeakLine($droidSpeak);

        // When we translate it to Galactic Basic;
        $galacticBasic = $droidSpeak->toGalacticBasic();

        // Then we should get the correct translation.
        self::assertSame($expectedGalacticBasic, $galacticBasic);
    }

    /**
     * @return array[]
     */
    public function droidSpeakProvider(): array
    {
        return [
            [
                <<<DROID
01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111
DROID
                ,
                'Cell 2187'
            ],
            [
                <<<DROID
 01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111
 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000
 01000001 01000001 00101101 00110010 00110011 00101100
DROID
                ,
                'Detention Block AA-23,'
            ],
            [
                <<<DROID
 01010100 01101000 01100101 01110011 01100101 00100000 01100001 01110010
 01100101 00100000 01101110 01101111 01110100 00100000 01110100 01101000
 01100101 00100000 01100100 01110010 01101111 01101001 01100100 01110011
 00100000 01111001 01101111 01110101 00100111 01110010 01100101 00100000
 01101100 01101111 01101111 01101011 01101001 01101110 01100111 00100000
 01100110 01101111 01110010 00101110 
DROID
                ,
                "These are not the droids you're looking for."
            ],
        ];
    }
}
