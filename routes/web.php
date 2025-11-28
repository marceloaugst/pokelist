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
    Route::post('/pokemons/search', [PokemonController::class, 'search'])->name('pokemons.search');
    Route::post('/pokemons', [PokemonController::class, 'store'])->name('pokemons.store');
    Route::get('/pokemons/{pokemon}', [PokemonController::class, 'show'])->name('pokemons.show');
    Route::delete('/pokemons/{pokemon}', [PokemonController::class, 'destroy'])->name('pokemons.destroy');
});

require __DIR__ . '/auth.php';
