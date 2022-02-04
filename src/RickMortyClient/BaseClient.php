<?php

namespace App\RickMortyClient;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface as CacheItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseClient implements ClientInterface
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

    abstract protected function createCollection(array $items, int $itemCount): CollectionInterface;

    abstract protected function map(object $item): ItemInterface;

    abstract protected function getApiEndpoint(): string;

    public function getAll(?int $offset = 0, ?int $count = 20): CollectionInterface
    {
        // This API is paged per 20. Squanch data until Schwifty.
        $page = (int)($offset / self::ITEMS_PER_PAGE) + 1;
        $pageOffset = $offset % self::ITEMS_PER_PAGE;
        $items = [];

        do {
            $allData = $this->getAllData($page);
            $itemCount = $allData->info->count;
            foreach (array_slice($allData->results, $pageOffset, $count - count($items)) as $item) {
                $items[] = $this->map($item);
            }
            $page++;
            $pageOffset = 0;
        } while (count($items) < $count && (count($items) + $offset) < $itemCount);

        return $this->createCollection($items, $itemCount);
    }


    public function get(int $id): ItemInterface
    {
        return $this->map($this->getData($id));
    }


    public function getBulk(array $urls): CollectionInterface
    {
        if (count($urls) === 0) {
            return $this->createCollection([], 0);
        }

        // Extract the Id's from the url's
        $ids = array_map(function ($url) {
            return $this->extractIdFromURL($url);
        }, $urls);

        $data = json_decode($this->doRequest(
            sprintf(
                "%s%s/%s",
                self::BASE_URL,
                $this->getApiEndpoint(),
                implode(",", $ids)
            )
        ));

        // The Api returns an object on a single id, and an array on multiple. Encapsulate to fix.
        if (count($urls) == 1) {
            $data = [$data];
        }

        return $this->createCollection(
            array_map(function ($item) {
                return $this->map($item);
            }, $data),
            count($data)
        );
    }

    protected function extractIdFromURL(string $url): int
    {
        return (int)substr($url, strrpos($url, "/") + 1);
    }

    private function doRequest(string $url): string
    {
        return $this->cache->get("httpcache-" . sha1($url), function (CacheItemInterface $item) use ($url) {
            $item->expiresAfter(3600);
            $respone = $this->httpClient->request(
                'GET',
                $url
            );

            return $respone->getContent();
        });
    }

    private function getAllData(int $page): object
    {
        return json_decode($this->doRequest(
            sprintf(
                "%s%s?page=%d",
                self::BASE_URL,
                $this->getApiEndpoint(),
                $page
            )
        ));
    }

    private function getData(int $id): object
    {
        return json_decode($this->doRequest(
            sprintf(
                "%s%s/%d",
                self::BASE_URL,
                $this->getApiEndpoint(),
                $id
            )
        ));
    }
}
