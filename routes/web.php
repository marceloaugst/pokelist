<?php

use App\Http\Controllers\PokedexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PokedexController::class, 'index'])->name('home');
Route::get('/pokedex/list', [PokedexController::class, 'list'])->name('pokedex.list');
Route::get('/pokedex/search', [PokedexController::class, 'search'])->name('pokedex.search');
Route::get('/pokedex/{pokemonId}', [PokedexController::class, 'show'])
    ->whereNumber('pokemonId')
    ->name('pokedex.show');
