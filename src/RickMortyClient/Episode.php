<?php

namespace App\RickMortyClient;

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
    public function getAirDate(): string
    {
        return $this->airDate;
    }

    /**
     * @return string
     */
    public function getEpisodeCode(): string
    {
        return $this->episodeCode;
    }

    /**
     * @return array
     */
    public function getCharacters(): array
    {
        return $this->characters;
    }
}
