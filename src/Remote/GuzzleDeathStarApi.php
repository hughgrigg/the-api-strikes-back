<?php

namespace DeathStarApi\Remote;

use DomainException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use stdClass;

/**
 * Client for interacting with the Death Star API via Guzzle.
 */
class GuzzleDeathStarApi implements DeathStarApi
{
    /** @var ClientInterface */
    private $client;

    /** @var CertFile */
    private $certFile;

    /** @var string */
    private $uri;

    /** @var Token */
    private $token;

    /**
     * @param ClientInterface $client
     * @param CertFile        $certFile
     * @param string          $uri
     */
    public function __construct(
        ClientInterface $client,
        CertFile $certFile,
        string $uri = DeathStarApi::URI
    ) {
        $this->client = $client;
        $this->certFile = $certFile;
        $this->uri = rtrim($uri, '/');
    }

    /**
     * Get an Oauth token.
     *
     * @param string $clientId
     * @param string $clientSecret
     *
     * @return Token
     */
    public function authorise(string $clientId, string $clientSecret): Token
    {
        try {
            $response = $this->client->request(
                'POST',
                "{$this->uri}/token",
                [
                    'headers'     => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept'       => 'application/json',
                    ],
                    // Sign the request with the SSL cert.
                    'ssl_key'     => $this->certFile->getPathname(),
                    // Use HTTP Basic Auth to pass credentials to the server.
                    'auth'        => [$clientId, $clientSecret],
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                    ],
                ]
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException(
                "Failure authorising with Death Star API: {$e->getMessage()}"
            );
        }

        $json = $this->decodeResponseJson($response);
        $fieldsSet = isset(
            $json->access_token,
            $json->expires_in,
            $json->token_type,
            $json->scope
        );
        if (!$fieldsSet) {
            throw new DomainException(
                'Bad auth response format from Death Star API.'
            );
        }

        $this->token = new Token(
            (string) $json->access_token,
            (int) $json->expires_in,
            (string) $json->token_type,
            (string) $json->scope
        );

        return $this->token;
    }

    /**
     * Blow up one of the Death Star's reactor exhausts.
     *
     * @param int $reactorExhaustIndex
     *
     * @return bool Whether the reactor exhaust blew up.
     */
    public function destroyReactorExhaust(int $reactorExhaustIndex): bool
    {
        try {
            $response = $this->client->request(
                'DELETE',
                "{$this->uri}/reactor/exhaust/{$reactorExhaustIndex}",
                [
                    'headers' => [
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                        'Authorization' => $this->authHeader(),
                        'x-torpedoes'   => 2,
                    ],
                    // Sign the request with the SSL cert.
                    'ssl_key' => $this->certFile->getPathname(),
                ]
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException(
                sprintf(
                    'Failure blowing up reactor exhaust %d: %s',
                    $reactorExhaustIndex,
                    $e->getMessage()
                )
            );
        }

        return $response->getStatusCode() === 204;
    }

    /**
     * Get the cell and block location of a prisoner in DroidSpeak.
     *
     * @param string $prisonerName
     *
     * @return PrisonerLocation
     */
    public function getPrisonerLocation(string $prisonerName): PrisonerLocation
    {
        try {
            $response = $this->client->request(
                'GET',
                "{$this->uri}/prisoner/{$prisonerName}",
                [
                    'headers' => [
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                        'Authorization' => $this->authHeader(),
                        'x-torpedoes'   => 2,
                    ],
                    // Sign the request with the SSL cert.
                    'ssl_key' => $this->certFile->getPathname(),
                ]
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException(
                sprintf(
                    'Failure retrieving prisoner location for %s: %s',
                    $prisonerName,
                    $e->getMessage()
                )
            );
        }

        $json = $this->decodeResponseJson($response);
        $fieldsSet = isset($json->cell, $json->block);
        if (!$fieldsSet) {
            throw new DomainException(
                'Bad prisoner response format from Death Star API.'
            );
        }

        return new PrisonerLocation($json->cell, $json->block);
    }

    /**
     * @param $response
     *
     * @return stdClass
     */
    private function decodeResponseJson(ResponseInterface $response): stdClass
    {
        $json = \json_decode($response->getBody());
        if (!$json) {
            throw new RuntimeException(
                sprintf(
                    "Failed to decode Death Star API response:\n%s\n\n%s\n",
                    \json_last_error_msg(),
                    (string) $response->getBody()
                )
            );
        }

        return $json;
    }

    /**
     * Get the content of the 'Authorization' HTTP header.
     *
     * @return string
     */
    private function authHeader(): string
    {
        if (empty($this->token->accessToken)) {
            throw new RuntimeException(
                'An authorisation token must be acquired first.'
            );
        }

        return "Bearer {$this->token->accessToken}";
    }
}
