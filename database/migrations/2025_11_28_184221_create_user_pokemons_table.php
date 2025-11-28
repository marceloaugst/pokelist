<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_pokemons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('pokemon_id'); // ID da PokéAPI
            $table->string('pokemon_name');
            $table->string('game_name'); // Nome do jogo onde pegou
            $table->string('sprite_url')->nullable();
            
            // Stats da PokéAPI (base stats)
            $table->integer('base_hp');
            $table->integer('base_attack');
            $table->integer('base_defense');
            $table->integer('base_sp_attack');
            $table->integer('base_sp_defense');
            $table->integer('base_speed');
            
            // Stats do jogo (stats reais capturadas)
            $table->integer('game_hp');
            $table->integer('game_attack');
            $table->integer('game_defense');
            $table->integer('game_sp_attack');
            $table->integer('game_sp_defense');
            $table->integer('game_speed');
            
            $table->integer('level')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_pokemons');
    }
};
