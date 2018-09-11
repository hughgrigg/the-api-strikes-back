<?php

namespace DeathStarApi\Translate;

class DroidSpeakLine
{
    /** @var string */
    private $droidSpeak;

    /**
     * @param string $droidSpeak
     */
    public function __construct(string $droidSpeak)
    {
        // Clean up DroidSpeak.
        $droidSpeak = preg_replace('/[\t\n\r\0\x0B]/', '', $droidSpeak);
        $droidSpeak = preg_replace('/([\s])\1+/', ' ', $droidSpeak);
        $droidSpeak = trim($droidSpeak);

        $this->droidSpeak = $droidSpeak;
    }

    /**
     * Get this DroidSpeak line translated to Galactic Basic.
     *
     * @return string
     */
    public function toGalacticBasic(): string
    {
        return $this->binBytesToChars($this->droidSpeak);
    }

    /**
     * @param string $binBytes
     * @param string $delimiter
     *
     * @return string
     */
    private function binBytesToChars(
        string $binBytes,
        string $delimiter = ' '
    ): string {
        return implode(
            '',
            array_map(
                function (string $binByte): string {
                    return $this->binByteToChar($binByte);
                },
                explode($delimiter, $binBytes)
            )
        );
    }

    /**
     * @param string $binByte
     *
     * @return string
     */
    private function binByteToChar(string $binByte): string
    {
        return \chr(base_convert($binByte, 2, 10));
    }
}
