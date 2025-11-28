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
            $table->json('evolution_chain')->nullable(); // Cadeia de evolução
            $table->json('mega_evolutions')->nullable(); // Mega evoluções disponíveis
            $table->json('pokedex_numbers')->nullable(); // Números da Pokédex de diferentes jogos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_pokemons', function (Blueprint $table) {
            $table->dropColumn(['evolution_chain', 'mega_evolutions', 'pokedex_numbers']);
        });
    }
};
