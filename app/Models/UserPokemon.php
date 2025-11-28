<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPokemon extends Model
{
    protected $table = 'user_pokemons';

    protected $fillable = [
        'user_id',
        'pokemon_id',
        'pokemon_name',
        'game_name',
        'sprite_url',
        'base_hp',
        'base_attack',
        'base_defense',
        'base_sp_attack',
        'base_sp_defense',
        'base_speed',
        'game_hp',
        'game_attack',
        'game_defense',
        'game_sp_attack',
        'game_sp_defense',
        'game_speed',
        'level',
        'notes',
        // Pokédex Data
        'species',
        'height',
        'weight',
        'abilities',
        'types',
        // Training
        'ev_yield',
        'catch_rate',
        'base_friendship',
        'base_experience',
        'growth_rate',
        // Breeding
        'egg_groups',
        'gender_male_rate',
        'gender_female_rate',
        'egg_cycles',
        'hatch_steps_min',
        'hatch_steps_max',
        // Type Defenses
        'type_defenses',
        // Evolution
        'evolution_chain',
        'mega_evolutions',
        'pokedex_numbers',
    ];

    protected $casts = [
        'pokemon_id' => 'integer',
        'base_hp' => 'integer',
        'base_attack' => 'integer',
        'base_defense' => 'integer',
        'base_sp_attack' => 'integer',
        'base_sp_defense' => 'integer',
        'base_speed' => 'integer',
        'game_hp' => 'integer',
        'game_attack' => 'integer',
        'game_defense' => 'integer',
        'game_sp_attack' => 'integer',
        'game_sp_defense' => 'integer',
        'game_speed' => 'integer',
        'level' => 'integer',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'abilities' => 'array',
        'types' => 'array',
        'catch_rate' => 'integer',
        'base_friendship' => 'integer',
        'base_experience' => 'integer',
        'egg_groups' => 'array',
        'gender_male_rate' => 'decimal:2',
        'gender_female_rate' => 'decimal:2',
        'egg_cycles' => 'integer',
        'hatch_steps_min' => 'integer',
        'hatch_steps_max' => 'integer',
        'type_defenses' => 'array',
        'evolution_chain' => 'array',
        'mega_evolutions' => 'array',
        'pokedex_numbers' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Calcula a porcentagem do stat do jogo em relação ao base stat
    public function getStatPercentage(string $stat): float
    {
        $baseStat = $this->{"base_$stat"};
        $gameStat = $this->{"game_$stat"};

        if ($baseStat == 0) return 0;

        return round(($gameStat / $baseStat) * 100, 2);
    }

    // Verifica se um stat está bom (acima de 80% do base)
    public function isStatGood(string $stat): bool
    {
        return $this->getStatPercentage($stat) >= 80;
    }
}
