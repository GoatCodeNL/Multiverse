<?php

namespace App\RickMortyClient\Dimension;

use App\RickMortyClient\ClientInterface;
use App\RickMortyClient\Location\LocationClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface as CacheItemInterface;

/**
 * The dimension client is an odd duck as there is no api endpoint to get it directly. So instead we work by retrieving
 * all location data and extract the dimension information from it.
 *
 * Dimensions stored in cache for performance and to prevent overload of the API.
 */
class DimensionClient implements ClientInterface
{
    private CacheInterface $cache;
    private LocationClient $locationClient;

    public function __construct(CacheInterface $cache, LocationClient $locationClient)
    {
        $this->cache = $cache;
        $this->locationClient = $locationClient;
    }

    public function getAll(?int $offset = 0, ?int $count = 20): DimensionCollection
    {
        $dimensions = $this->retrieveAllDimensions();

        return new DimensionCollection(array_splice($dimensions, $offset, $count), count($dimensions));
    }

    public function get(int $id): Dimension
    {
        $dimensions = $this->retrieveAllDimensions();

        if (array_key_exists($id, $dimensions) == false) {
            throw new \Exception("Dimension not found");
        }

        return $dimensions[$id];
    }

    public function getBulk(array $urls): DimensionCollection
    {
        throw new \Exception("There is no such thing as a dimension URL!");
    }

    private function retrieveAllDimensions(): array
    {
        $dimensions = $this->cache->get("dimensions-list", function (CacheItemInterface $item) {
            $offset = 0;
            $dimensions = [];
            do {
                $locations = $this->locationClient->getAll($offset);
                foreach ($locations as $location) {
                    $dimensionId = Dimension::generateId($location->getDimension());
                    if (array_key_exists($dimensionId, $dimensions) == false) {
                        $dimensions[$dimensionId] = new Dimension($dimensionId, $location->getDimension());
                    }
                    $dimensions[$dimensionId]->addLocation($location);
                    $offset++;
                }
            } while ($locations->getItemCount() > $offset);

            return $dimensions;
        });


        return $dimensions;
    }
}
