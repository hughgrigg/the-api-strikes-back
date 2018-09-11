<?php

namespace DeathStarApi\Tests\Unit;

use DeathStarApi\Container;
use DeathStarApi\Remote\DeathStarApi;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class UnitTest extends TestCase
{
    /** @var Container */
    protected $container;

    /** @var DeathStarApi|MockObject */
    protected $deathStarApi;

    /**
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        parent::setUp();

        // Use fresh container for each test.
        $this->container = new Container();

        // Set env vars in container.
        $this->container
            ->setEnv(Container::DEATH_STAR_ID, 'R2D2')
            ->setEnv(Container::DEATH_STAR_SECRET, 'Alderan');


        // Bind the mock API into the container.
        $this->deathStarApi = $this->createMock(DeathStarApi::class);
        $this->container->bind(DeathStarApi::class, function (): DeathStarApi {
            return $this->deathStarApi;
        });
    }
}
