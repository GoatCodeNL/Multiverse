<?php

namespace App\RickMortyClient;

class LocationCollection extends Collection
{
    public function current(): Location
    {
        return parent::current();
    }
}
