<?php

namespace App\RickMortyClient\Dimension;

use App\RickMortyClient\Collection;

class DimensionCollection extends Collection
{
    public function current(): Dimension
    {
        return parent::current();
    }
}
