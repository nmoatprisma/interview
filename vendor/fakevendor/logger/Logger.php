<?php

declare(strict_types=1);

namespace Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    public function log($level, string|Stringable $message, array $context = []): void
    {
        /** @phpstan-ignore-next-line */
        printf("LOGGER: log() [%s] %s (%s)\n", (string)$level, $message, json_encode($context));
    }
}
