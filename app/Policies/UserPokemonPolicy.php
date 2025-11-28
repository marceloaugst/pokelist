<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserPokemon;

class UserPokemonPolicy
{
    public function view(User $user, UserPokemon $userPokemon): bool
    {
        return $user->id === $userPokemon->user_id;
    }

    public function delete(User $user, UserPokemon $userPokemon): bool
    {
        return $user->id === $userPokemon->user_id;
    }
}
