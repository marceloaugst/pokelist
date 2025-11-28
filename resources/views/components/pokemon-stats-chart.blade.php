@props(['pokemon', 'gameStats' => null, 'interactive' => false])

<div class="pokemon-stats-chart relative">
    <div class="mb-4 text-center">
        <h3 class="text-xl font-bold">Status do Pokémon</h3>
        @if ($interactive)
            <p class="text-sm text-gray-600">Os valores em vermelho mostram seus stats atuais</p>
        @endif
    </div>

    <!-- SVG Chart -->
    <div class="relative mx-auto" style="width: 400px; height: 400px;">
        <svg width="400" height="400" viewBox="0 0 400 400" class="absolute inset-0">
            <!-- Grid lines -->
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="#e5e7eb" stroke-width="0.5" />
                </pattern>
            </defs>

            <!-- Background grid circles -->
            @for ($i = 1; $i <= 5; $i++)
                <polygon points="200,50 350,170 290,330 110,330 50,170" fill="none" stroke="#e5e7eb" stroke-width="1"
                    transform="scale({{ $i * 0.2 }}) translate({{ 200 - $i * 40 }}, {{ 200 - $i * 40 }})" />
            @endfor

            <!-- Axis lines -->
            <line x1="200" y1="200" x2="200" y2="50" stroke="#d1d5db" stroke-width="1" />
            <line x1="200" y1="200" x2="350" y2="170" stroke="#d1d5db" stroke-width="1" />
            <line x1="200" y1="200" x2="290" y2="330" stroke="#d1d5db" stroke-width="1" />
            <line x1="200" y1="200" x2="110" y2="330" stroke="#d1d5db" stroke-width="1" />
            <line x1="200" y1="200" x2="50" y2="170" stroke="#d1d5db" stroke-width="1" />

            <!-- Base stats polygon (blue) -->
            @php
                // Convert stats to coordinates
                $baseStats = [
                    'speed' => $pokemon->base_speed ?? 50,
                    'attack' => $pokemon->base_attack ?? 50,
                    'sp_attack' => $pokemon->base_sp_attack ?? 50,
                    'defense' => $pokemon->base_defense ?? 50,
                    'hp' => $pokemon->base_hp ?? 50,
                ];

                $maxStat = 200; // Maximum stat value for scaling
                $center = 200;
                $radius = 150;

                // Calculate points for pentagon
                $angleStep = (2 * pi()) / 5;
                $startAngle = -pi() / 2; // Start at top

                $basePoints = [];
                $gamePoints = [];
                $statNames = ['speed', 'attack', 'sp_attack', 'defense', 'hp'];

                foreach ($statNames as $index => $statName) {
                    $angle = $startAngle + $index * $angleStep;
                    $baseValue = $baseStats[$statName];
                    $baseScale = min($baseValue / $maxStat, 1);

                    $x = $center + $radius * $baseScale * cos($angle);
                    $y = $center + $radius * $baseScale * sin($angle);
                    $basePoints[] = round($x, 1) . ',' . round($y, 1);

                    // Game stats (if provided)
                    if ($gameStats) {
                        $gameValue = $gameStats['game_' . $statName] ?? $baseValue;
                        $gameScale = min($gameValue / $maxStat, 1);
                        $gameX = $center + $radius * $gameScale * cos($angle);
                        $gameY = $center + $radius * $gameScale * sin($angle);
                        $gamePoints[] = round($gameX, 1) . ',' . round($gameY, 1);
                    }
                }

                $basePointsStr = implode(' ', $basePoints);
                $gamePointsStr = $gameStats ? implode(' ', $gamePoints) : '';
            @endphp

            <!-- Base stats polygon (blue) -->
            <polygon points="{{ $basePointsStr }}" fill="rgba(59, 130, 246, 0.3)" stroke="#3b82f6" stroke-width="2" />

            <!-- Game stats polygon (red) - only if game stats are provided -->
            @if ($gameStats && $gamePointsStr)
                <polygon points="{{ $gamePointsStr }}" fill="rgba(239, 68, 68, 0.3)" stroke="#ef4444"
                    stroke-width="2" />
            @endif

            <!-- Stat value points -->
            @foreach ($statNames as $index => $statName)
                @php
                    $angle = $startAngle + $index * $angleStep;
                    $baseValue = $baseStats[$statName];
                    $baseScale = min($baseValue / $maxStat, 1);
                    $x = $center + $radius * $baseScale * cos($angle);
                    $y = $center + $radius * $baseScale * sin($angle);
                @endphp

                <!-- Base stat point -->
                <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="#3b82f6" stroke="white"
                    stroke-width="2" />

                <!-- Game stat point (if provided) -->
                @if ($gameStats)
                    @php
                        $gameValue = $gameStats['game_' . $statName] ?? $baseValue;
                        $gameScale = min($gameValue / $maxStat, 1);
                        $gameX = $center + $radius * $gameScale * cos($angle);
                        $gameY = $center + $radius * $gameScale * sin($angle);
                    @endphp
                    <circle cx="{{ $gameX }}" cy="{{ $gameY }}" r="4" fill="#ef4444" stroke="white"
                        stroke-width="2" />
                @endif
            @endforeach
        </svg>

        <!-- Stat Labels -->
        <div class="pointer-events-none absolute inset-0">
            <!-- Speed (Top) -->
            <div class="absolute" style="top: 20px; left: 50%; transform: translateX(-50%);">
                <span class="text-sm font-semibold text-gray-700">Velocidade</span>
            </div>

            <!-- Attack (Top Right) -->
            <div class="absolute" style="top: 120px; right: 20px;">
                <span class="text-sm font-semibold text-gray-700">Ataque</span>
            </div>

            <!-- Sp. Attack (Bottom Right) -->
            <div class="absolute" style="bottom: 40px; right: 80px;">
                <span class="text-sm font-semibold text-gray-700">Atq. Esp.</span>
            </div>

            <!-- Defense (Bottom Left) -->
            <div class="absolute" style="bottom: 40px; left: 80px;">
                <span class="text-sm font-semibold text-gray-700">Defesa</span>
            </div>

            <!-- HP (Top Left) -->
            <div class="absolute" style="top: 120px; left: 20px;">
                <span class="text-sm font-semibold text-gray-700">HP</span>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-4 flex justify-center gap-6">
        <div class="flex items-center gap-2">
            <div class="h-4 w-4 rounded bg-blue-500"></div>
            <span class="text-sm font-medium">Status Base (PokéAPI)</span>
        </div>
        @if ($gameStats)
            <div class="flex items-center gap-2">
                <div class="h-4 w-4 rounded bg-red-500"></div>
                <span class="text-sm font-medium">Status no Jogo</span>
            </div>
        @endif
    </div>

    <!-- Stats Table -->
    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
        @php
            $statLabels = [
                'hp' => 'HP',
                'attack' => 'Ataque',
                'defense' => 'Defesa',
                'sp_attack' => 'Ataque Especial',
                'speed' => 'Velocidade',
            ];
        @endphp

        @foreach ($statLabels as $stat => $label)
            @php
                $baseStat = $baseStats[$stat];
                $gameStat = $gameStats ? $gameStats['game_' . $stat] ?? $baseStat : null;
                $percentage = $gameStat ? round(($gameStat / $baseStat) * 100) : 100;

                if ($percentage >= 90) {
                    $color = 'green';
                    $status = 'Excelente';
                } elseif ($percentage >= 80) {
                    $color = 'blue';
                    $status = 'Muito Bom';
                } elseif ($percentage >= 70) {
                    $color = 'indigo';
                    $status = 'Bom';
                } elseif ($percentage >= 60) {
                    $color = 'yellow';
                    $status = 'Médio';
                } else {
                    $color = 'red';
                    $status = 'Fraco';
                }
            @endphp

            <div class="rounded-lg border p-3">
                <div class="mb-2 flex items-center justify-between">
                    <span class="font-semibold">{{ $label }}</span>
                    @if ($gameStat)
                        <span class="text-{{ $color }}-600 text-sm font-medium">{{ $status }}</span>
                    @endif
                </div>

                <div class="flex gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Base:</span>
                        <span class="font-bold text-blue-600">{{ $baseStat }}</span>
                    </div>
                    @if ($gameStat)
                        <div>
                            <span class="text-gray-600">Jogo:</span>
                            <span class="font-bold text-red-600">{{ $gameStat }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">%:</span>
                            <span class="text-{{ $color }}-600 font-bold">{{ $percentage }}%</span>
                        </div>
                    @endif
                </div>

                @if ($gameStat)
                    <div class="mt-2 h-2 w-full rounded-full bg-gray-200">
                        <div class="bg-{{ $color }}-500 h-2 rounded-full transition-all duration-300"
                            style="width: {{ min($percentage, 100) }}%"></div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

@if ($interactive)
    <script>
        function updateStatsChart(statName, value) {
            // Esta função será implementada para atualizar o gráfico em tempo real
            // quando os inputs forem alterados na página de criação
            console.log(`Updating ${statName} to ${value}`);
        }
    </script>
@endif
