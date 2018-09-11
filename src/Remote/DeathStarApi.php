<?php

namespace DeathStarApi\Remote;

/**
 * Client for interacting with the Death Star API.
 */
interface DeathStarApi
{
    public const URI = '​https://death.star.api';

    /**
     * Get an Oauth token.
     *
     * @param string $clientId
     * @param string $clientSecret
     *
     * @return Token
     */
    public function authorise(string $clientId, string $clientSecret): Token;

    /**
     * Blow up one of the Death Star's reactor exhausts.
     *
     * @param int $reactorExhaustIndex
     *
     * @return bool Whether the reactor exhaust blew up.
     */
    public function destroyReactorExhaust(int $reactorExhaustIndex): bool;

    /**
     * Get the cell and block location of a prisoner in DroidSpeak.
     *
     * @param string $prisonerName
     *
     * @return PrisonerLocation
     */
    public function getPrisonerLocation(string $prisonerName): PrisonerLocation;
}
