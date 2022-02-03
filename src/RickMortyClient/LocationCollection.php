<?php

namespace App\RickMortyClient;

class LocationCollection implements \Iterator
{
    private int $position = 0;
    private array $items = [];
    private int $itemCount;

    public function __construct($items, $itemCount)
    {
        $this->items = $items;
        $this->itemCount = $itemCount;
    }

    public function current(): Location
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->position < count($this->items);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
