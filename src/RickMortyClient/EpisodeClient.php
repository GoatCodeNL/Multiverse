<?php

namespace App\RickMortyClient;

/**
 * @method static Episode get(int $id)
 * @method static EpisodeCollection getAll(?int $offset = 0, ?int $count = 20)
 * @method static EpisodeCollection getBulk(array $urls)
 */
class EpisodeClient extends BaseClient
{
    protected function createCollection($items, $itemCount): EpisodeCollection {
        return new EpisodeCollection($items, $itemCount);
    }

    protected function map(object $item): Episode
    {
        dump($item);
        return new Episode(
            id: $item->id,
            name: $item->name,
            airDate: $item->air_date,
            episodeCode: $item->episode,
            characters: $item->characters,
        );
    }

    protected function getApiEndpoint(): string
    {
        return "episode";
    }
}
