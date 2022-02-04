<?php

namespace App\RickMortyClient;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    private const ITEMS_PER_PAGE = 20;
    const BASE_URL = "https://rickandmortyapi.com/api/";

    private HttpClientInterface $httpClient;
    private CacheInterface $cache;

    public function __construct(HttpClientInterface $httpClient, CacheInterface $cache)
    {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    public function getLocations(?int $offset = 0, ?int $count = 20): LocationCollection
    {
        $page = (int)($offset / self::ITEMS_PER_PAGE) + 1;
        $pageOffset = $offset % self::ITEMS_PER_PAGE;
        $items = [];

        do {
            $locationsData = $this->getLocationsData($page);
            $itemCount = $locationsData->info->count;
            foreach (array_slice($locationsData->results, $pageOffset, $count - count($items)) as $item) {
                $items[] = $this->mapLocation($item);
            }
            $page++;
            $pageOffset = 0;
        } while (count($items) < $count && (count($items) + $offset) < $itemCount);

        return new LocationCollection($items, $itemCount);
    }

    public function getLocation(int $locationId): Location
    {
        return $this->mapLocation($this->getLocationData($locationId));
    }

    private function getLocationsData(int $page): object
    {
        return json_decode($this->doRequest(
            'https://rickandmortyapi.com/api/location?page=' . $page
        ));
    }

    private function getLocationData(int $id): object
    {
        return json_decode($this->doRequest(
            'https://rickandmortyapi.com/api/location/' . $id
        ));
    }

    public function mapLocation(object $item): Location
    {
        return new Location(
            id: $item->id,
            name: $item->name,
            type: $item->type,
            dimension: $item->dimension,
            residents: $item->residents,
        );
    }


    public function getCharactersBulk(array $characterUrls): CharacterCollection
    {
        // Strip the Id's from the url's
        $ids = array_map(function ($characterUrl) {
            return substr($characterUrl, strrpos($characterUrl, "/") + 1);
        }, $characterUrls);

        $charactersData = json_decode($this->doRequest(
            'https://rickandmortyapi.com/api/character/' . implode(",", $ids)
        ));

        return new CharacterCollection(
            array_map(function ($characterData) {
                return $this->mapCharacter($characterData);
            }, $charactersData),
            count($charactersData)
        );
    }

    public function mapCharacter(object $item): Character
    {
        return new Character(
            id: $item->id,
            name: $item->name,
            status: $item->status,
            species: $item->species,
            type: $item->type,
            gender: $item->gender,
            origin: $item->origin,
            location: $item->location,
            image: $item->image,
            episode: $item->episode
        );
    }

    public function getCharacters(?int $offset = 0, ?int $count = 20): CharacterCollection
    {
        $page = (int)($offset / self::ITEMS_PER_PAGE) + 1;
        $pageOffset = $offset % self::ITEMS_PER_PAGE;
        $items = [];

        do {
            $characterData = $this->getCharactersData($page);
            $itemCount = $characterData->info->count;
            foreach (array_slice($characterData->results, $pageOffset, $count - count($items)) as $item) {
                $items[] = $this->mapCharacter($item);
            }
            $page++;
            $pageOffset = 0;
        } while (count($items) < $count && (count($items) + $offset) < $itemCount);

        return new CharacterCollection($items, $itemCount);
    }

    public function getCharacter(int $characterId): Character
    {
        return $this->mapCharacter($this->getCharacterData($characterId));
    }

    private function getCharactersData(int $page)
    {
        return json_decode($this->doRequest(
            'https://rickandmortyapi.com/api/character?page=' . $page
        ));
    }

    private function getCharacterData(int $id): object
    {
        return json_decode($this->doRequest(
            self::BASE_URL . 'character/' . $id
        ));
    }

    public function getDimensions(): DimensionCollection
    {

    }

    public function getDimension(int $dimensionId): Dimension
    {

    }

    private function doRequest(string $url): string
    {
        return $this->cache->get("httpcache-".sha1($url), function (ItemInterface $item) use ($url) {
            $item->expiresAfter(3600);
            $respone = $this->httpClient->request(
                'GET',
                $url
            );

            return $respone->getContent();
        });
    }


}
