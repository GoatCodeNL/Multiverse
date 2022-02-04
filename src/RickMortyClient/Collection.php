<?php

namespace App\RickMortyClient;


abstract class Collection implements CollectionInterface
{
    private int $position = 0;
    private array $items = [];
    private int $itemCount;

    public function __construct($items, $itemCount)
    {
        $this->items = $items;
        $this->itemCount = $itemCount;
    }

    public function current(): mixed
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

    public function valid(): bool
    {
        return $this->position < count($this->items);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function getItemCount(): int
    {
        return $this->itemCount;
    }
}
