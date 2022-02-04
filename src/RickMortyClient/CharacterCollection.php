<?php

namespace App\RickMortyClient;

class CharacterCollection extends Collection
{
    public function current(): Character
    {
        return parent::current();
    }
}
