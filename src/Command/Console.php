<?php

namespace DeathStarApi\Command;

use DeathStarApi\Container;
use OutOfBoundsException;

class Console
{
    /** @var array */
    private $argv;

    /** @var Container */
    private $container;

    /** @var string[] */
    private $commands = [
        Authorise::class,
        DeleteReactorExhaust::class,
        GetPrisonerLocation::class,
        TranslateDroidSpeak::class,
    ];

    /**
     * @param string[]  $argv
     * @param Container $container
     */
    public function __construct(array $argv, Container $container)
    {
        $this->argv = $argv;
        $this->container = $container;
    }

    /**
     * @throws \OutOfBoundsException
     */
    public function execute(): void
    {
        if (!$this->commandName() || $this->commandName() === 'help') {
            $this->help();

            return;
        }

        foreach ($this->commands as $commandClass) {
            if (\constant("{$commandClass}::NAME") === $this->commandName()) {
                $this->makeCommand($commandClass)->run();

                return;
            }
        }

        throw new OutOfBoundsException(
            "There is no command called `{$this->commandName()}`."
        );
    }

    /**
     * @return string
     */
    private function commandName(): string
    {
        if (isset($this->argv[1])) {
            return (string) $this->argv[1];
        }

        return '';
    }

    /**
     * Print a help message.
     */
    private function help(): void
    {
        echo "Available commands:\n";
        foreach ($this->commands as $commandClass) {
            printf(
                "%s\n\t%s\n\n",
                \constant("{$commandClass}::NAME"),
                \constant("{$commandClass}::DESCRIPTION")
            );
        }
    }

    /**
     * @param string $commandClass
     *
     * @return Command
     */
    private function makeCommand(string $commandClass): Command
    {
        return new $commandClass($this->container, $this->argv);
    }
}
