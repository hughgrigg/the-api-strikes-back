<?php

namespace DeathStarApi\Command;

use DeathStarApi\Translate\DroidSpeakLine;

class TranslateDroidSpeak extends Command
{
    public const NAME = 'translate:droid-speak';
    public const DESCRIPTION = <<<'DOC'
Translate input lines of DroidSpeak into Galactic basic.
DOC;

    public function run(): void
    {
        foreach ($this->input() as $line) {
            $droidSpeak = new DroidSpeakLine($line);
            $this->output("{$droidSpeak->toGalacticBasic()}\n");
        }
    }
}
