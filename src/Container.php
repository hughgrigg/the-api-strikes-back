<?php

namespace DeathStarApi;

use DeathStarApi\Remote\CertFile;
use DeathStarApi\Remote\DeathStarApi;
use DeathStarApi\Remote\SplCertFile;
use InvalidArgumentException;
use RuntimeException;

/**
 * Basic application container with config.
 */
class Container
{
    public const DEATH_STAR_ID = 'DEATH_STAR_ID';
    public const DEATH_STAR_SECRET = 'DEATH_STAR_SECRET';
    public const DEATH_STAR_CERT_FILE = 'DEATH_STAR_CERT_FILE';
    public const DEATH_STAR_URI = 'DEATH_STAR_URI';

    /** @var callable[] */
    private $bindings;

    /** @var string[] */
    private $envVars = [];

    /**
     * @param string   $identifier
     * @param callable $make
     *
     * @return Container
     */
    public function bind(string $identifier, callable $make): self
    {
        $this->bindings[$identifier] = $make;

        return $this;
    }

    /**
     * @param string $identifier
     *
     * @return mixed
     */
    public function make(string $identifier)
    {
        if (array_key_exists($identifier, $this->bindings)) {
            $make = $this->bindings[$identifier];

            return $make($this);
        }

        return new $identifier();
    }

    /**
     * @param string $envName
     * @param string $value
     *
     * @return Container
     */
    public function setEnv(string $envName, string $value): self
    {
        $this->envVars[$envName] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function clientId(): string
    {
        return $this->getEnv(self::DEATH_STAR_ID);
    }

    /**
     * @return string
     */
    public function clientSecret(): string
    {
        return $this->getEnv(self::DEATH_STAR_SECRET);
    }

    /**
     * @return CertFile
     */
    public function certFile(): CertFile
    {
        $file = new SplCertFile($this->getEnv(self::DEATH_STAR_CERT_FILE));
        if (!$file->isReadable()) {
            throw new RuntimeException(
                "Unable to read cert file at {$file->getPathname()}"
            );
        }

        return $file;
    }

    /**
     * @return string
     */
    public function uri(): string
    {
        $envUri = $this->getEnv(self::DEATH_STAR_URI);
        $uri = $envUri ?: DeathStarApi::URI;
        /* @noinspection BypassedUrlValidationInspection */
        if (\filter_var($uri, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException(
                "Bad Death Star URI provided: {$uri}"
            );
        }

        return $uri;
    }

    /**
     * @param string $envName
     *
     * @return string
     */
    private function getEnv(string $envName): string
    {
        if (empty($this->envVars[$envName])) {
            $this->envVars[$envName] = (string) getenv($envName);
        }

        return $this->envVars[$envName];
    }
}
