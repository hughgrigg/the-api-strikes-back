<?php

namespace DeathStarApi\Command;

class DeleteReactorExhaust extends Command
{
    public const NAME = 'delete:reactor-exhaust';
    public const DESCRIPTION = <<<DOC
Delete one of the Death Star's reactor exhausts.

\tArgument: {int} reactor exhaust number
DOC;

    public function run(): void
    {
        $exhaustIndex = (int) $this->argument(0);
        $this->output(
            "Destroying Death Star reactor exhaust #{$exhaustIndex}...\n"
        );

        $this->api()->authorise(
            $this->container->clientId(),
            $this->container->clientSecret()
        );

        $destroyed = $this->api()->destroyReactorExhaust($exhaustIndex);
        if ($destroyed) {
            $this->output(
                "Destroyed Death Star reactor exhaust #{$exhaustIndex}!\n"
            );

            return;
        }

        $this->output(
            "Failed to destroy Death Star reactor exhaust #{$exhaustIndex}.\n"
        );
    }
}
