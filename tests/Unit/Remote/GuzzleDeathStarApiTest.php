<?php

namespace DeathStarApi\Tests\Unit\Remote;

use DeathStarApi\Remote\CertFile;
use DeathStarApi\Remote\GuzzleDeathStarApi;
use DeathStarApi\Tests\Unit\UnitTest;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;

class GuzzleDeathStarApiTest extends UnitTest
{
    /** @var ClientInterface|MockObject */
    private $client;

    /** @var CertFile|MockObject */
    private $certFile;

    /** @var GuzzleDeathStarApi */
    private $guzzleDeathStarApi;

    /** @var Response */
    private $authResponse;

    /**
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = $this->createMock(ClientInterface::class);
        $this->certFile = $this->createMock(CertFile::class);
        $this->certFile->expects($this->any())
            ->method('getPathname')
            ->willReturn('client.pem');

        $this->guzzleDeathStarApi = new GuzzleDeathStarApi(
            $this->client,
            $this->certFile
        );

        $this->authResponse = new Response(
            201,
            [],
            <<<'JSON'
{
    "access_token": "e31a726c4b90462ccb7619e1b51f3d0068bf8006",
    "expires_in": 99999999999,
    "token_type": "Bearer",
    "scope": "TheForce"
}
JSON
        );
    }

    /**
     * Should be able to get an auth token.
     */
    public function testAuthorise(): void
    {
        // Given the Death Star API will return a valid auth response;
        $this->client->expects($this->any())
            ->method('request')
            ->with(
                'POST',
                $this->stringEndsWith('/token'),
                $this->callback(
                    function (array $options): bool {
                        self::assertEquals(
                            'client.pem',
                            $options['ssl_key']
                        );
                        self::assertEquals(
                            ['R2D2', 'Alderan'],
                            $options['auth']
                        );

                        return true;
                    }
                )
            )
            ->willReturn($this->authResponse);

        // When we request an auth token using the Guzzle Death Star API;
        $token = $this->guzzleDeathStarApi->authorise('R2D2', 'Alderan');

        // Then we should get an Oauth2 access token.
        self::assertEquals(
            'e31a726c4b90462ccb7619e1b51f3d0068bf8006',
            $token->accessToken
        );
    }

    /**
     * Should be able to destroy a reactor exhaust.
     */
    public function testDestroyReactorExhaust(): void
    {
        // Given the Death Star API will respond to a delete reactor exhaust
        // request with a positive result;
        $this->client->expects($this->any())
            ->method('request')
            ->withConsecutive(
                $this->anything(), // not interested in the auth here.
                [
                    'DELETE',
                    $this->stringEndsWith('/reactor/exhaust/42'),
                    $this->callback(
                        function (array $options): bool {
                            self::assertEquals(
                                'client.pem',
                                $options['ssl_key']
                            );
                            self::assertStringStartsWith(
                                'Bearer ',
                                $options['headers']['Authorization']
                            );
                            self::assertEquals(
                                2,
                                $options['headers']['x-torpedoes']
                            );

                            return true;
                        }
                    ),
                ]
            )
            ->willReturnOnConsecutiveCalls(
                $this->authResponse,
                new Response(204)
            );

        // When we destroy a reactor exhaust using the Guzzle Death Star API;
        $this->guzzleDeathStarApi->authorise('R2D2', 'Alderan');
        $destroyed = $this->guzzleDeathStarApi->destroyReactorExhaust(42);

        // Then the reactor should have been destroyed.
        self::assertTrue($destroyed);
    }

    /**
     * Should be able to get a prisoner location in DroidSpeak.
     */
    public function testGetPrisonerLocation(): void
    {
        // Given the Death Star API will respond to a prisoner location request
        // with a DroidSpeak location.
        $cellDroidSpeak = <<<'CELL'
01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111
CELL;
        $blockDroidSpeak = <<<'BLOCK'
01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110
00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001
00101101 00110010 00110011 00101100
BLOCK;
        $this->client->expects($this->any())
            ->method('request')
            ->withConsecutive(
                $this->anything(), // not interested in the auth here.
                [
                    'GET',
                    $this->stringEndsWith('/prisoner/leia'),
                    $this->callback(
                        function (array $options): bool {
                            self::assertEquals(
                                'client.pem',
                                $options['ssl_key']
                            );
                            self::assertStringStartsWith(
                                'Bearer ',
                                $options['headers']['Authorization']
                            );

                            return true;
                        }
                    ),
                ]
            )
            ->willReturnOnConsecutiveCalls(
                $this->authResponse,
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            'cell'  => $cellDroidSpeak,
                            'block' => $blockDroidSpeak,
                        ]
                    )
                )
            );

        // When we get a prisoner location using the Guzzle Death Star API;
        $this->guzzleDeathStarApi->authorise('R2D2', 'Alderan');
        $location = $this->guzzleDeathStarApi->getPrisonerLocation('leia');

        // Then we should get the location in DroidSpeak.
        self::assertEquals($cellDroidSpeak, $location->cellDroidSpeak);
        self::assertEquals($blockDroidSpeak, $location->blockDroidSpeak);
    }
}
