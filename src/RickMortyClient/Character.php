<?php

namespace App\RickMortyClient;

class Character implements ItemInterface
{
    public function __construct(
        private int    $id, // The id of the character.
        private string $name, // The name of the character.
        private string $status, // The status of the character ('Alive', 'Dead' or 'unknown').
        private string $species, // The species of the character.
        private string $type, // The type or subspecies of the character.
        private string $gender, // The gender of the character ('Female', 'Male', 'Genderless' or 'unknown').
        private object $origin, // Name and link to the character's origin location.
        private int $originId, // Name and link to the character's origin location.
        private object $location, // Name and link to the character's last known location endpoint.
        private int $locationId, // Name and link to the character's last known location endpoint.
        private string $image, // (url)	Link to the character's image. All images are 300x300px and most are medium shots or portraits since they are intended to be used as avatars.
        private array  $episode, // (urls)	List of episodes in which this character appeared.id
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getOrigin(): object
    {
        return $this->origin;
    }

    public function getLocation(): object
    {
        return $this->location;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getEpisode(): array
    {
        return $this->episode;
    }

    public function getOriginId(): int
    {
        return $this->originId;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }


}
