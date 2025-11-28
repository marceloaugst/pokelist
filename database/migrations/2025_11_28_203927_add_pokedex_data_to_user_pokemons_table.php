<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_pokemons', function (Blueprint $table) {
            // Pokédex Data
            $table->string('species')->nullable(); // Espécie (ex: "Seed Pokémon")
            $table->decimal('height', 5, 2)->nullable(); // Altura em metros
            $table->decimal('weight', 6, 2)->nullable(); // Peso em kg
            $table->json('abilities')->nullable(); // Habilidades
            $table->json('types')->nullable(); // Tipos do Pokémon

            // Training
            $table->string('ev_yield')->nullable(); // EV yield (ex: "1 Sp. Atk")
            $table->integer('catch_rate')->nullable(); // Taxa de captura
            $table->integer('base_friendship')->nullable(); // Amizade base
            $table->integer('base_experience')->nullable(); // Base Exp
            $table->string('growth_rate')->nullable(); // Taxa de crescimento

            // Breeding
            $table->json('egg_groups')->nullable(); // Grupos de ovo
            $table->decimal('gender_male_rate', 5, 2)->nullable(); // Taxa de macho
            $table->decimal('gender_female_rate', 5, 2)->nullable(); // Taxa de fêmea
            $table->integer('egg_cycles')->nullable(); // Ciclos de ovo
            $table->integer('hatch_steps_min')->nullable(); // Passos mínimos para chocar
            $table->integer('hatch_steps_max')->nullable(); // Passos máximos para chocar

            // Type Defenses (multiplicadores de dano)
            $table->json('type_defenses')->nullable(); // Efetividade de cada tipo contra este Pokémon
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_pokemons', function (Blueprint $table) {
            $table->dropColumn([
                'species',
                'height',
                'weight',
                'abilities',
                'types',
                'ev_yield',
                'catch_rate',
                'base_friendship',
                'base_experience',
                'growth_rate',
                'egg_groups',
                'gender_male_rate',
                'gender_female_rate',
                'egg_cycles',
                'hatch_steps_min',
                'hatch_steps_max',
                'type_defenses',
            ]);
        });
    }
};
