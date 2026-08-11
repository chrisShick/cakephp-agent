<?php

declare(strict_types=1);

namespace CakePhpAgent\Editor;

use InvalidArgumentException;

final class EditorRegistry
{
    /** @var array<string, EditorAdapterInterface> */
    private array $adapters;

    /**
     * @param list<EditorAdapterInterface>|null $adapters
     */
    public function __construct(?array $adapters = null)
    {
        $list = $adapters ?? [
            new CursorAdapter(),
            new ClaudeAdapter(),
            new CodexAdapter(),
        ];

        $this->adapters = [];
        foreach ($list as $adapter) {
            $this->adapters[$adapter->id()] = $adapter;
        }
    }

    public function get(string $id): EditorAdapterInterface
    {
        if (!isset($this->adapters[$id])) {
            throw new InvalidArgumentException(sprintf(
                'Unknown editor "%s". Supported: %s',
                $id,
                implode(', ', array_keys($this->adapters))
            ));
        }

        return $this->adapters[$id];
    }

    /**
     * @param list<string> $ids
     * @return list<EditorAdapterInterface>
     */
    public function resolveMany(array $ids): array
    {
        $resolved = [];
        foreach ($ids as $id) {
            $resolved[] = $this->get($id);
        }

        return $resolved;
    }

    /**
     * @return list<string>
     */
    public function ids(): array
    {
        return array_keys($this->adapters);
    }
}
