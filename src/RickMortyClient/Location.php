<?php

namespace App\RickMortyClient;

class Location
{
    public function __construct(
        private int    $id, // The id of the location.
        private string $name, // The name of the location.
        private string $type, // The type of the location.
        private string $dimension, // The dimension in which the location is located.
        private array  $residents, // List of character who have been last seen in the location.
        private string $url, // Link to the location's own endpoint.
        private string $created // Time at which the location was created in the database.
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDimension(): string
    {
        return $this->dimension;
    }

    /**
     * @return array
     */
    public function getResidents(): array
    {
        return $this->residents;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }
}
