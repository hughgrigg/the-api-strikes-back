<?php

namespace DeathStarApi\Remote;

/**
 * A certificate file used for requests against the Death Star API
 */
interface CertFile
{
    /**
     * Get the path to the cert PEM file.
     *
     * @return string
     */
    public function getPathname();
}
