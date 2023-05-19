<?php

declare(strict_types=1);

namespace Framework;

interface ServiceContainerInterface
{
    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    public function get(string $className): mixed;

    /**
     * @param array<string, callable> $factories
     */
    public function loadConfiguration(array $factories): static;

    public function setFactory(string $serviceName, callable $factoryFunction): static;
}
