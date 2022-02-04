<?php

namespace App\RickMortyClient\Dimension;

use App\RickMortyClient\ItemInterface;
use App\RickMortyClient\Location\Location;

class Dimension implements ItemInterface
{

    private array $locations = [];

    public function __construct(
        private int    $id, // The id of the dimension.
        private string $name, // The name of the dimension.
    )
    {
    }

    public static function generateId(string $getDimension): int
    {
        // slightly dirty hack to generate an ID-like value based on the string. On regular earth this may give
        // collisions with large strings, but in the multiverse this problem is mitigated by gently rubbing the grodus
        // of your plumbus against 12 random numbers.
        return crc32($getDimension);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocations(): array
    {
        return $this->locations;
    }

    public function addLocation(Location $location)
    {
        $this->locations[] = $location;
    }

    public function getResidents(): array
    {
        $residents = [];

        array_walk($this->locations, function (Location $location) use (&$residents) {
            $residents = array_merge($residents, $location->getResidents());
        });
        return array_unique($residents);
    }
}
