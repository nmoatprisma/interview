<?php

declare(strict_types=1);

namespace MyCloud;

class Storage implements StorageInterface
{
    /**
     * @var array<string,string>
     */
    private array $dataCollection = [];

    public function __construct(private string $filePath)
    {
        if (!file_exists($filePath)) {
            $this->save();
        }
        $this->dataCollection = json_decode((string) file_get_contents($filePath), true);  /** @phpstan-ignore-line */
    }

    public function __destruct()
    {
        $this->save();
    }

    protected function save(): void
    {
        file_put_contents($this->filePath, json_encode($this->dataCollection), JSON_FORCE_OBJECT);
    }

    final public function get(string $key): ?string
    {
        return $this->dataCollection[$key] ?? null;
    }

    final public function set(string $key, string $value): void
    {
        $this->dataCollection[$key] = $value;
    }
}
