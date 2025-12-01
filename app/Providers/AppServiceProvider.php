<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $commands = [
        //     'route:clear',
        //     'cache:clear',
        //     'config:clear',
        //     'view:clear',
        // ];

        // foreach ($commands as $command) {
        //     $status = Artisan::call($command);
        //     if ($status === 0) {
        //         Log::info("Comando '{$command}' executado com sucesso.");
        //     } else {
        //         Log::error("Falha ao executar o comando '{$command}'. Código de status: {$status}");
        //     }
        // }
    }
}
