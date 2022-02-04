<?php

namespace App\RickMortyClient\Location;

use App\RickMortyClient\Dimension\Dimension;
use App\RickMortyClient\ItemInterface;

class Location implements ItemInterface
{
    public function __construct(
        private int    $id, // The id of the location.
        private string $name, // The name of the location.
        private string $type, // The type of the location.
        private string $dimension, // The dimension in which the location is located.
        private array  $residents, // List of character who have been last seen in the location.
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDimension(): string
    {
        return $this->dimension;
    }

    public function getDimensionId(): int
    {
        return Dimension::generateId($this->dimension);
    }

    public function getResidents(): array
    {
        return $this->residents;
    }
}
