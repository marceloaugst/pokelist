<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('pokemons.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Pokémon
    Route::get('/pokemons', [PokemonController::class, 'index'])->name('pokemons.index');
    Route::get('/pokemons/create', [PokemonController::class, 'create'])->name('pokemons.create');
    Route::get('/pokemons/search-suggestions', [PokemonController::class, 'searchSuggestions'])->name('pokemons.search.suggestions');
    Route::get('/pokemons/get-pokemon', [PokemonController::class, 'getPokemonForCreate'])->name('pokemons.get-pokemon');
    Route::post('/pokemons/search', [PokemonController::class, 'search'])->name('pokemons.search');
    Route::post('/pokemons', [PokemonController::class, 'store'])->name('pokemons.store');
    Route::get('/pokemons/{pokemon}', [PokemonController::class, 'show'])->name('pokemons.show');
    Route::delete('/pokemons/{pokemon}', [PokemonController::class, 'destroy'])->name('pokemons.destroy');

    // Rotas do Team
    Route::get('/team', [PokemonController::class, 'team'])->name('pokemons.team');
    Route::post('/pokemons/{pokemon}/add-to-team', [PokemonController::class, 'addToTeam'])->name('pokemons.add-to-team');
    Route::delete('/pokemons/{pokemon}/remove-from-team', [PokemonController::class, 'removeFromTeam'])->name('pokemons.remove-from-team');

    // Rotas da Pokédx
    Route::get('/pokedex', [PokemonController::class, 'pokedex'])->name('pokedex.index');
    Route::get('/pokedex/load-more', [PokemonController::class, 'loadMorePokedex'])->name('pokedex.load-more');
});

require __DIR__ . '/auth.php';
