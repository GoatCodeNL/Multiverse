<?php

namespace App\RickMortyClient;

interface ClientInterface
{
    public function getAll(?int $offset = 0, ?int $count = 20): CollectionInterface;
    public function get(int $id): ItemInterface;
    public function getBulk(array $urls): CollectionInterface;
}
