<?php

namespace DeathStarApi\Command;

class GetPrisonerLocation extends Command
{
    public const NAME = 'get:prisoner';
    public const DESCRIPTION = <<<DOC
Get the location of a prisoner in DroidSpeak.

\tArgument: {string} prisoner name
DOC;

    public function run(): void
    {
        $prisonerName = $this->argument(0);

        $this->api()->authorise(
            $this->container->clientId(),
            $this->container->clientSecret()
        );

        $prisonerLocation = $this->api()->getPrisonerLocation($prisonerName);
        $this->output(
            "{$prisonerLocation->cellDroidSpeak}\n"
        );
        $this->output(
            "{$prisonerLocation->blockDroidSpeak}\n"
        );
    }
}
