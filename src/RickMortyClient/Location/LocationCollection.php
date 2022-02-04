<?php

namespace App\RickMortyClient\Location;

use App\RickMortyClient\Collection;

class LocationCollection extends Collection
{
    public function current(): Location
    {
        return parent::current();
    }
}
