<?php

namespace DeathStarApi\Remote;

/**
 * The location of a prisoner returned by the Death Star API, in DroidSpeak.
 */
class PrisonerLocation
{
    /** @var string */
    public $cellDroidSpeak;

    /** @var string */
    public $blockDroidSpeak;

    /**
     * @param string $cellDroidSpeak
     * @param string $blockDroidSpeak
     */
    public function __construct(string $cellDroidSpeak, string $blockDroidSpeak)
    {
        $this->cellDroidSpeak = $cellDroidSpeak;
        $this->blockDroidSpeak = $blockDroidSpeak;
    }
}
