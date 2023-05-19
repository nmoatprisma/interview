<?php

declare(strict_types=1);

namespace Framework;

use Exception;

class ServiceContainer implements ServiceContainerInterface
{
    /** @var array<string, callable>  */
    protected array $factories = [];

    /** @var array<string, mixed>  */
    protected array $services = [];

    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    public function get(string $className): mixed
    {
        if (!isset($this->factories[$className])) {
            throw new \Exception("$className: unknown service");
        }
        if (!isset($this->services[$className])) {
            $this->services[$className] = $this->factories[$className]();
        }
        return $this->services[$className];
    }

    /**
     * @param array<string, callable> $factories
     */
    public function loadConfiguration(array $factories): static
    {
        foreach ($factories as $serviceName => $factoryFunction) {
            $this->setFactory($serviceName, $factoryFunction);
        }
        return $this;
    }

    public function setFactory(string $serviceName, callable $factoryFunction): static
    {
        $this->factories[$serviceName] = $factoryFunction;
        return $this;
    }
}
