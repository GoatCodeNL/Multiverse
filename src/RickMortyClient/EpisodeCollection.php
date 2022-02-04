<?php

namespace App\RickMortyClient;

class EpisodeCollection extends Collection
{
    public function current(): Episode
    {
        return parent::current();
    }
}
