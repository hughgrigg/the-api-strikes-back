<?php

namespace DeathStarApi\Tests\Unit\Command;

use DeathStarApi\Command\DeleteReactorExhaust;

class DeleteReactorExhaustTest extends CommandTest
{
    /** @var DeleteReactorExhaust */
    private $deleteReactorExhaustCommand;

    protected function setUp()
    {
        parent::setUp();

        $this->deleteReactorExhaustCommand = new DeleteReactorExhaust(
            $this->container,
            ['', '', 42], // argv
            $this->mockInput(),
            $this->mockOutput()
        );
    }

    /**
     * Should see that the reactor exhaust is destroyed.
     */
    public function testReactorExhaustDestruction(): void
    {
        // Given the Death Star API will respond to a delete reactor exhaust
        // request with a positive result;
        $this->deathStarApi->expects($this->any())
            ->method('destroyReactorExhaust')
            ->with(42)
            ->willReturn(true);

        // When the delete reactor exhaust command is run;
        $this->deleteReactorExhaustCommand->run();

        // Then we should see that the reactor exhaust was destroyed.
        self::assertContains(
            'Destroyed Death Star reactor exhaust #42!',
            $this->output
        );
    }

    /**
     * Should see that the reactor exhaust is not destroyed.
     */
    public function testFailedReactorExhaustDestruction(): void
    {
        // Given the Death Star API will respond to a delete reactor exhaust
        // request with a negative result;
        $this->deathStarApi->expects($this->any())
            ->method('destroyReactorExhaust')
            ->with(42)
            ->willReturn(false);

        // When the delete reactor exhaust command is run;
        $this->deleteReactorExhaustCommand->run();

        // Then we should see that the reactor exhaust was not destroyed.
        self::assertNotContains(
            'Destroyed Death Star reactor exhaust #42!',
            $this->output
        );
        self::assertContains(
            'Failed to destroy Death Star reactor exhaust #42.',
            $this->output
        );
    }
}
