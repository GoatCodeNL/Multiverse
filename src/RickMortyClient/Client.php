<?php

namespace App\RickMortyClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    private const ITEMS_PER_PAGE = 20;

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getLocations(?int $offset = 0, ?int $count = 20): LocationCollection
    {
        $page = (int)($offset / self::ITEMS_PER_PAGE) + 1;
        $pageOffset = $offset;
        $items = [];

        do {
            $locationsData = $this->getLocationsData($page);
            $itemCount = $locationsData->info->count;
            foreach(array_slice($locationsData->results, $pageOffset, $count - count($items)) as $item) {
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
        $response = $this->httpClient->request(
            'GET',
            'https://rickandmortyapi.com/api/location?page=' . $page
        );

        return json_decode($response->getContent());
    }

    private function getLocationData(int $id): object
    {
        $response = $this->httpClient->request(
            'GET',
            'https://rickandmortyapi.com/api/location/' . $id
        );

        return json_decode($response->getContent());
    }

    public function getCharacters(): CharacterCollection
    {

    }

    public function getCharacter(int $characterId): Character
    {

    }

    public function getDimensions(): DimensionCollection
    {

    }

    public function getDimension(int $dimensionId): Dimension
    {

    }

    public function mapLocation(object $item): Location
    {
        return new Location(
            id: $item->id,
            name: $item->name,
            type: $item->type,
            dimension: $item->dimension,
            residents: $item->residents,
            url: $item->url,
            created: $item->created
        );
    }
}
