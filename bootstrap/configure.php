<?php

require_once __DIR__.'/../vendor/autoload.php';

use DeathStarApi\Container;
use DeathStarApi\Remote\DeathStarApi;
use DeathStarApi\Remote\GuzzleDeathStarApi;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

$container = new Container();
$container->bind(
    DeathStarApi::class,
    function (Container $container): DeathStarApi {
        return new GuzzleDeathStarApi(
            $container->make(ClientInterface::class),
            $container->certFile(),
            $container->uri()
        );
    }
);
$container->bind(
    ClientInterface::class,
    function (): ClientInterface {
        return new Client();
    }
);

return $container;
