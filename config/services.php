<?php

declare(strict_types=1);

$container = new Framework\ServiceContainer();

return $container->loadConfiguration([
    \MyCloud\Storage::class => fn () => new \MyCloud\Storage(filePath: __DIR__ . '/../var/storage.json'),
    // \Logger\Logger::class  => fn () => new \Logger\Logger(),
    \App\Service\SecretRepository::class => fn () => new \App\Service\SecretRepository($container->get(\MyCloud\Storage::class)),
    \App\Command\GetCommand::class => fn () => new \App\Command\GetCommand($container->get(\App\Service\SecretRepository::class)),
    \App\Command\SetCommand::class => fn () => new \App\Command\SetCommand($container->get(\App\Service\SecretRepository::class)),
]);
