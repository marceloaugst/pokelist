<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTE DA POKEAPI - CHARMANDER (ID: 4) ===\n\n";

// Teste 1: Requisição HTTP direta
echo "1. Testando requisição HTTP direta (file_get_contents)...\n";
try {
   $response = @file_get_contents('https://pokeapi.co/api/v2/pokemon/4');
   if ($response) {
      $json = json_decode($response, true);
      echo "   ✓ Sucesso! Charmander encontrado\n";
      echo "   Nome: " . ucfirst($json['name']) . "\n";
      echo "   HP Base: " . $json['stats'][0]['base_stat'] . "\n\n";
   } else {
      echo "   ✗ Falhou\n\n";
   }
} catch (\Exception $e) {
   echo "   ✗ Erro: " . $e->getMessage() . "\n\n";
}

// Teste 2: Laravel HTTP Client
echo "2. Testando Laravel HTTP Client...\n";
try {
   $response = \Illuminate\Support\Facades\Http::timeout(10)->get('https://pokeapi.co/api/v2/pokemon/4');
   if ($response->successful()) {
      $data = $response->json();
      echo "   ✓ Sucesso! Charmander encontrado\n";
      echo "   Nome: " . ucfirst($data['name']) . "\n";
      echo "   HP Base: " . $data['stats'][0]['base_stat'] . "\n\n";
   } else {
      echo "   ✗ Falhou - Status: " . $response->status() . "\n\n";
   }
} catch (\Exception $e) {
   echo "   ✗ Erro: " . $e->getMessage() . "\n";
   echo "   Classe: " . get_class($e) . "\n\n";
}

// Teste 3: PokeApiService
echo "3. Testando PokeApiService...\n";
$service = new App\Services\PokeApiService();
try {
   $data = $service->getPokemon(4);

   if ($data === null) {
      echo "   ✗ Retornou NULL\n\n";
   } else {
      echo "   ✓ Sucesso!\n\n";
      echo "=== DADOS DO CHARMANDER ===\n";
      echo "Nome: " . $data['name'] . "\n";
      echo "ID: " . $data['id'] . "\n";
      echo "Sprite: " . ($data['sprite'] ?? 'N/A') . "\n";
      echo "Tipos: " . implode(', ', $data['types']) . "\n";
      echo "Fraquezas: " . implode(', ', $data['weaknesses']) . "\n\n";

      echo "=== STATS BASE ===\n";
      echo "HP: " . $data['stats']['hp'] . "\n";
      echo "Attack: " . $data['stats']['attack'] . "\n";
      echo "Defense: " . $data['stats']['defense'] . "\n";
      echo "Special Attack: " . $data['stats']['sp_attack'] . "\n";
      echo "Special Defense: " . $data['stats']['sp_defense'] . "\n";
      echo "Speed: " . $data['stats']['speed'] . "\n\n";

      echo "✓ TESTE COMPLETO - TUDO FUNCIONANDO!\n";
   }
} catch (\Exception $e) {
   echo "   ✗ Erro: " . $e->getMessage() . "\n";
   echo "   Trace: " . $e->getTraceAsString() . "\n";
}
