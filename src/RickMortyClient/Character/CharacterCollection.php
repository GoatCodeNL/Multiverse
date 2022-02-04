<?php

namespace App\RickMortyClient\Character;

use App\RickMortyClient\Collection;

class CharacterCollection extends Collection
{
    public function current(): Character
    {
        return parent::current();
    }
}
