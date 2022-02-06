<?php

namespace App\Tests\Mocks;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MockCache implements CacheInterface
{
    /**
     * @var callable
     */
    private $getCallable;
    /**
     * @var callable
     */
    private $deleteCallable;

    public function __construct(?callable $getCallable = null, ?callable $deleteCallable = null)
    {
        $this->getCallable = $getCallable;
        $this->deleteCallable = $deleteCallable;
    }

    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null): mixed
    {
        if ($this->getCallable === null) {
            return call_user_func($callback, new MockCacheItem());
        }
        return call_user_func_array($this->getCallable, [$key, $callback, $beta, $metadata]);
    }

    public function delete(string $key): bool
    {
        if ($this->deleteCallable === null) {
            return false;
        }
        return call_user_func_array($this->deleteCallable, [$key]);
    }
}
