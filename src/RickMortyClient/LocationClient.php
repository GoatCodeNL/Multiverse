<?php

namespace App\RickMortyClient;

/**
 * @method static Location get(int $id)
 * @method static LocationCollection getAll(?int $offset = 0, ?int $count = 20)
 * @method static LocationCollection getBulk(array $urls)
 */
class LocationClient extends BaseClient
{
    protected function createCollection($items, $itemCount): LocationCollection
    {
        return new LocationCollection($items, $itemCount);
    }

    protected function map(object $item): Location
    {
        return new Location(
            id: $item->id,
            name: $item->name,
            type: property_exists($item, 'type') ? $item->type : "", // For some reason this property does not always exist
            dimension: property_exists($item, 'dimension') ? $item->dimension : "", // For some reason this property does not always exist
            residents: $item->residents,
        );
    }

    protected function getApiEndpoint(): string
    {
        return "location";
    }

}
