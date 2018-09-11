<?php

namespace DeathStarApi\Remote;

/**
 * An Oauth token returned by the Death Star API auth endpoint.
 */
class Token
{
    /** @var string */
    public $accessToken;

    /** @var int */
    public $expiresIn;

    /** @var string */
    public $tokenType;

    /** @var string */
    public $scope;

    /**
     * @param string $accessToken
     * @param int    $expiresIn
     * @param string $tokenType
     * @param string $scope
     */
    public function __construct(
        string $accessToken,
        int $expiresIn,
        string $tokenType,
        string $scope
    ) {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;
        $this->scope = $scope;
    }
}
