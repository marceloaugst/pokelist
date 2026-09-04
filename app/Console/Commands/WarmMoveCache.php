<?php

namespace App\Console\Commands;

use App\Services\PokeApiService;
use Illuminate\Console\Command;

class WarmMoveCache extends Command
{
    protected $signature = 'pokedex:warm-moves';

    protected $description = 'Pré-carrega no cache os detalhes de todos os movimentos, deixando a aba Moves instantânea';

    public function handle(PokeApiService $pokeApi): int
    {
        $this->info('Buscando a lista de movimentos…');

        $moves = $pokeApi->getAllMoveUrls();

        if (empty($moves)) {
            $this->error('Não foi possível carregar a lista de movimentos da PokéAPI.');
            return self::FAILURE;
        }

        $missing = $pokeApi->filterUncachedMoves($moves);
        $cachedCount = count($moves) - count($missing);

        $this->line(sprintf('%d movimentos no total, %d já em cache.', count($moves), $cachedCount));

        if (empty($missing)) {
            $this->info('Cache já está completo.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($missing));
        $bar->start();

        foreach (array_chunk($missing, PokeApiService::MOVE_POOL_SIZE, true) as $chunk) {
            $pokeApi->getMoveDetailsBatch($chunk);
            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->newLine(2);
        $this->info(sprintf('%d movimentos adicionados ao cache.', count($missing)));

        return self::SUCCESS;
    }
}
