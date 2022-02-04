<?php

namespace App\RickMortyClient;

/**
 * @method Character get(int $id)
 * @method CharacterCollection getAll(?int $offset = 0, ?int $count = 20)
 * @method CharacterCollection getBulk(array $urls)
 */
class CharacterClient extends BaseClient
{
    protected function createCollection($items, $itemCount): CharacterCollection
    {
        return new CharacterCollection($items, $itemCount);
    }

    protected function map(object $item): Character
    {
        return new Character(
            id: $item->id,
            name: $item->name,
            status: $item->status,
            species: $item->species,
            type: $item->type,
            gender: $item->gender,
            origin: $item->origin,
            originId: $this->extractIdFromURL($item->origin->url),
            location: $item->location,
            locationId: $this->extractIdFromURL($item->location->url),
            image: $item->image,
            episode: $item->episode
        );
    }

    protected function getApiEndpoint(): string
    {
        return "character";
    }
}
