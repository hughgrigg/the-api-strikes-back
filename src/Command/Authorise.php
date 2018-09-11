<?php

namespace DeathStarApi\Command;

class Authorise extends Command
{
    public const NAME = 'authorise';
    public const DESCRIPTION = 'Get an Oauth2 token from the Death Star.';

    public function run(): void
    {
        $this->output("Fetching auth token from Death Star...\n");
        $token = $this->api()->authorise(
            $this->container->clientId(),
            $this->container->clientSecret()
        );

        $this->output("{$token->accessToken}\n");
    }
}
