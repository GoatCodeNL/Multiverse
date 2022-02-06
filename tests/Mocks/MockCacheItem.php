<?php

namespace App\Tests\Mocks;

use Symfony\Contracts\Cache\ItemInterface;

class MockCacheItem implements ItemInterface
{

    public function getKey(): string
    {
        // TODO: Implement getKey() method.
    }

    public function get(): mixed
    {
        // TODO: Implement get() method.
    }

    public function isHit(): bool
    {
        // TODO: Implement isHit() method.
    }

    public function set(mixed $value): static
    {
        return $this;
    }

    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        return $this;
    }

    public function expiresAfter(\DateInterval|int|null $time): static
    {
        return $this;
    }

    public function tag(iterable|string $tags): static
    {
        return $this;
    }

    public function getMetadata(): array
    {
        // TODO: Implement getMetadata() method.
    }
}
