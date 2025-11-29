<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('user_teams', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_pokemon_id')->constrained()->onDelete('cascade');
      $table->integer('position')->default(1); // Posição no time (1-6)
      $table->timestamps();

      // Um usuário só pode ter um Pokémon por posição
      $table->unique(['user_id', 'position']);
      // Um Pokémon só pode estar em um time
      $table->unique(['user_pokemon_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('user_teams');
  }
};
