<?php

namespace App\RickMortyClient\Episode;

use App\RickMortyClient\Collection;

class EpisodeCollection extends Collection
{
    public function current(): Episode
    {
        return parent::current();
    }
}
