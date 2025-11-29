<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTeam extends Model
{
  protected $fillable = [
    'user_id',
    'user_pokemon_id',
    'position',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function userPokemon(): BelongsTo
  {
    return $this->belongsTo(UserPokemon::class);
  }
}
