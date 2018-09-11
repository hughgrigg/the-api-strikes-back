<?php

namespace DeathStarApi\Command;

use DeathStarApi\Container;
use DeathStarApi\Remote\DeathStarApi;
use Generator;
use RuntimeException;

abstract class Command
{
    /** @var Container */
    protected $container;

    /** @var array|string[] */
    protected $argv;

    /** @var Generator|resource */
    protected $inputHandle;

    /** @var Generator|resource */
    protected $outputHandle;

    /** @var string[] */
    protected $arguments;

    /** @var DeathStarApi */
    private $api;

    /**
     * @param Container        $container
     * @param string[]         $argv
     * @param Generator|string $input
     * @param Generator|string $output
     */
    public function __construct(
        Container $container,
        array $argv = [],
        $input = 'php://stdin',
        $output = 'php://output'
    ) {
        $this->container = $container;
        $this->argv = $argv;

        // Assume a string for input or output is a resource handle.
        $this->inputHandle = $input;
        if (\is_string($this->inputHandle)) {
            $this->inputHandle = fopen($this->inputHandle, 'rb');
        }
        $this->outputHandle = $output;
        if (\is_string($output)) {
            $this->outputHandle = fopen($this->outputHandle, 'wb+');
        }
    }

    /**
     * Close the output resource.
     */
    public function __destruct()
    {
        if (\is_resource($this->inputHandle)) {
            fclose($this->inputHandle);
        }
        if (\is_resource($this->outputHandle)) {
            fclose($this->outputHandle);
        }
    }

    abstract public function run(): void;

    /**
     * @return Generator|string[]
     */
    protected function input(): Generator
    {
        if ($this->inputHandle instanceof Generator) {
            foreach ($this->inputHandle as $line) {
                yield $line;
            }

            return;
        }

        while ($line = fgets($this->inputHandle)) {
            yield $line;
        }
    }

    /**
     * @param string $content
     */
    protected function output(string $content): void
    {
        if ($this->outputHandle instanceof Generator) {
            $this->outputHandle->send($content);

            return;
        }

        fwrite($this->outputHandle, $content);
    }

    /**
     * @return DeathStarApi
     */
    protected function api(): DeathStarApi
    {
        if ($this->api === null) {
            $this->api = $this->container->make(DeathStarApi::class);
        }

        return $this->api;
    }

    /**
     * Get the value of a command-line argument.
     *
     * @param int $index
     *
     * @return string
     */
    protected function argument(int $index): string
    {
        // Skip the first two arguments (console.php and command name).
        $index += 2;

        if (isset($this->argv[$index])) {
            return (string) $this->argv[$index];
        }

        throw new RuntimeException('Missing required argument');
    }
}
