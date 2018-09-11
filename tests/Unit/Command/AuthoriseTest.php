<?php

namespace DeathStarApi\Tests\Unit\Command;

use DeathStarApi\Command\Authorise;
use DeathStarApi\Remote\Token;

class AuthoriseTest extends CommandTest
{
    /** @var Authorise */
    private $authoriseCommand;

    protected function setUp()
    {
        parent::setUp();

        $this->authoriseCommand = new Authorise(
            $this->container,
            [],  // argv
            $this->mockInput(),
            $this->mockOutput()
        );
    }

    /**
     * Should get the oauth token in the authorise command output.
     */
    public function testGetsAuthToken(): void
    {
        // Given the Death Star API will respond to an auth request with an
        // auth token;
        $this->deathStarApi->expects($this->any())
            ->method('authorise')
            ->with('R2D2', 'Alderan')
            ->willReturn(
                new Token(
                    'e31a726c4b90462ccb7619e1b51f3d0068bf8006',
                    99999999999,
                    'Bearer',
                    'TheForce'
                )
            );

        // When the authorise command is run;
        $this->authoriseCommand->run();

        // Then we should get the auth token in the output.
        self::assertContains(
            'e31a726c4b90462ccb7619e1b51f3d0068bf8006',
            $this->output
        );
    }
}
