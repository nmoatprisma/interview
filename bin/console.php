#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$serviceContainer = require __DIR__ . '/../config/services.php';

$application = new Application();

// ... register commands
$application->add($serviceContainer->get(App\Command\GetCommand::class));
$application->add($serviceContainer->get(App\Command\SetCommand::class));

$application->run();
