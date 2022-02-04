<?php

namespace App\RickMortyClient\Episode;

use App\RickMortyClient\ItemInterface;

class Episode implements ItemInterface
{

    public function __construct(
        private int    $id, // The id of the episode.
        private string $name, // The name of the episode.
        private string $airDate, // The air date of the episode.
        private string $episodeCode, // The code of the episode.
        private array  $characters, // (urls)	List of characters who have been seen in the episode.
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

    public function getAirDate(): string
    {
        return $this->airDate;
    }

    public function getEpisodeCode(): string
    {
        return $this->episodeCode;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }
}
