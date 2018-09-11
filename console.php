#!/usr/bin/env php
<?php

$container = require __DIR__.'/bootstrap/configure.php';

use DeathStarApi\Command\Console;

(new Console($argv, $container))->execute();
