<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div class="flex items-center gap-4">
        <div
          class="flex h-16 w-16 animate-pulse items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-purple-500 shadow-lg shadow-blue-500/30">
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
            </path>
          </svg>
        </div>
        <div>
          <h2 class="text-3xl font-bold text-white">
            Pokédex Nacional
          </h2>
          <p class="text-blue-100">Descubra todos os Pokémon</p>
        </div>
      </div>
      <div class="text-right text-white">
        <span class="text-sm text-blue-200">Total de Pokémon carregados:</span>
        <div class="text-xl font-bold" id="pokemon-count">{{ count($pokemons) }}</div>
      </div>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <!-- Barra de Pesquisa -->
      <div
        class="mb-6 overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm sm:rounded-2xl">
        <div class="p-6">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <input type="text" id="pokemon-search" placeholder="Pesquise por nome ou número do Pokémon..."
              class="block w-full pl-10 pr-3 py-3 border border-slate-600 rounded-lg bg-slate-700/50 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
              <button id="clear-search" class="text-slate-400 hover:text-white transition-colors duration-200"
                style="display: none;">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
          <div id="search-status" class="mt-2 text-sm text-slate-400" style="display: none;"></div>
        </div>
      </div>

      <div class="overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm sm:rounded-2xl">
        <div class="p-6 text-slate-200">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="pokemon-grid">
            <!-- Pokémons serão carregados via JavaScript -->
          </div>

          <!-- Loading indicator -->
          <div id="loading-indicator" class="mt-8 text-center" style="display: none;">
            <div class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white">
              <div class="h-4 w-4 animate-spin rounded-full border-2 border-blue-500 border-t-transparent"></div>
              Carregando mais Pokémon...
            </div>
          </div>

          <!-- End message -->
          <div id="end-message" class="mt-8 text-center" style="display: none;">
            <p class="text-slate-400">Todos os Pokémon foram carregados!</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    /* Estilos específicos para a Pokédex Nacional */
    .pokedex-card-national {
      background: linear-gradient(135deg,
          rgba(30, 41, 59, 0.95) 0%,
          rgba(51, 65, 85, 0.8) 50%,
          rgba(30, 41, 59, 0.95) 100%);
      border: 1px solid rgba(148, 163, 184, 0.3);
      border-radius: 1.2rem;
      backdrop-filter: blur(12px);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      min-height: 320px;
      text-decoration: none;
      color: inherit;
    }

    .pokedex-card-national:hover {
      text-decoration: none;
      color: inherit;
    }

    .pokedex-card-national::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg,
          rgba(59, 130, 246, 0.1) 0%,
          transparent 50%,
          rgba(139, 92, 246, 0.1) 100%);
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 1;
    }

    .pokedex-card-national:hover::before {
      opacity: 1;
    }

    .pokedex-card-national:hover {
      transform: translateY(-8px) scale(1.02);
      border-color: rgba(59, 130, 246, 0.6);
      box-shadow:
        0 20px 40px -10px rgba(0, 0, 0, 0.4),
        0 0 30px rgba(59, 130, 246, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .pokedex-card-national>* {
      position: relative;
      z-index: 2;
    }

    .pokemon-sprite {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.4));
    }

    .group:hover .pokemon-sprite {
      transform: scale(1.15) rotate(5deg);
      filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.5));
    }

    .type-badge,
    .weakness-badge {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .type-badge::before,
    .weakness-badge::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.6s;
    }

    .pokedex-card-national:hover .type-badge::before,
    .pokedex-card-national:hover .weakness-badge::before {
      left: 100%;
    }

    #loading-indicator {
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.7;
      }
    }

    .pokedex-card-national {
      animation: slideInUp 0.6s ease-out forwards;
      opacity: 0;
      transform: translateY(30px);
    }

    @keyframes slideInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      .pokedex-card-national {
        margin: 0 auto;
        max-width: 300px;
        min-height: 280px;
      }

      .pokedex-card-national:hover {
        transform: translateY(-4px) scale(1.01);
      }
    }
  </style>

  <script>
    let loading = false;
        let currentOffset = {{ count($pokemons) }};
        let hasMoreData = true;
        let searchTimeout;
        let isSearching = false;
        let originalPokemons = @json($pokemons);

        // Função para obter cor do tipo
        const typeColors = {
            'normal': '#A8A878',
            'fire': '#F08030',
            'water': '#6890F0',
            'electric': '#F8D030',
            'grass': '#78C850',
            'ice': '#98D8D8',
            'fighting': '#C03028',
            'poison': '#A040A0',
            'ground': '#E0C068',
            'flying': '#A890F0',
            'psychic': '#F85888',
            'bug': '#A8B820',
            'rock': '#B8A038',
            'ghost': '#705898',
            'dragon': '#7038F8',
            'dark': '#705848',
            'steel': '#B8B8D0',
            'fairy': '#EE99AC',
        };

        function getTypeColor(type) {
            return typeColors[type] || '#777';
        }

        // Função para pesquisar Pokémon
        async function searchPokemons(query) {
            if (!query.trim()) {
                clearSearch();
                return;
            }

            isSearching = true;
            document.getElementById('search-status').style.display = 'block';
            document.getElementById('search-status').textContent = 'Pesquisando...';

            try {
                const response = await fetch(`{{ route('pokedex.search') }}?query=${encodeURIComponent(query)}`);
                const results = await response.json();

                const grid = document.getElementById('pokemon-grid');
                grid.innerHTML = '';

                if (results.length === 0) {
                    grid.innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <div class="text-slate-400">
                                <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold mb-2">Nenhum Pokémon encontrado</h3>
                                <p>Tente pesquisar por outro nome ou número.</p>
                            </div>
                        </div>
                    `;
                    document.getElementById('search-status').textContent = 'Nenhum resultado encontrado';
                } else {
                    results.forEach((pokemon, index) => {
                        const card = createPokemonCard(pokemon);
                        setTimeout(() => {
                            grid.appendChild(card);
                            card.offsetHeight;
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 50);
                    });
                    document.getElementById('search-status').textContent = `${results.length} Pokémon encontrado${results.length > 1 ? 's' : ''}`;
                }

            } catch (error) {
                console.error('Erro na pesquisa:', error);
                document.getElementById('search-status').textContent = 'Erro na pesquisa. Tente novamente.';
            }
        }

        // Função para limpar pesquisa
        function clearSearch() {
            isSearching = false;
            document.getElementById('pokemon-search').value = '';
            document.getElementById('clear-search').style.display = 'none';
            document.getElementById('search-status').style.display = 'none';

            const grid = document.getElementById('pokemon-grid');
            grid.innerHTML = '';

            // Recarregar Pokémons originais
            originalPokemons.forEach((pokemon, index) => {
                const card = createPokemonCard(pokemon);
                setTimeout(() => {
                    grid.appendChild(card);
                    card.offsetHeight;
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 20);
            });
        }

        // Event listeners para pesquisa
        document.getElementById('pokemon-search').addEventListener('input', function(e) {
            const query = e.target.value;

            // Mostrar/esconder botão de limpar
            document.getElementById('clear-search').style.display = query ? 'block' : 'none';

            // Cancelar pesquisa anterior
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Pesquisar com delay para evitar muitas requisições
            searchTimeout = setTimeout(() => {
                if (query.length >= 2) {
                    searchPokemons(query);
                } else if (query.length === 0) {
                    clearSearch();
                }
            }, 300);
        });

        document.getElementById('clear-search').addEventListener('click', clearSearch);

        // Função para carregar mais Pokémon (apenas quando não estiver pesquisando)
        async function loadMorePokemon() {
            if (loading || !hasMoreData || isSearching) return;

            loading = true;
            document.getElementById('loading-indicator').style.display = 'block';

            try {
                const response = await fetch(`{{ route('pokedex.load-more') }}?offset=${currentOffset}&limit=20`);
                const data = await response.json();

                if (data.length === 0) {
                    hasMoreData = false;
                    document.getElementById('loading-indicator').style.display = 'none';
                    document.getElementById('end-message').style.display = 'block';
                    return;
                }

                const grid = document.getElementById('pokemon-grid');

                data.forEach((pokemon, index) => {
                    const card = createPokemonCard(pokemon);
                    setTimeout(() => {
                        grid.appendChild(card);
                        card.offsetHeight;
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                currentOffset += data.length;
                document.getElementById('pokemon-count').textContent = currentOffset;

            } catch (error) {
                console.error('Erro ao carregar Pokémon:', error);
            } finally {
                loading = false;
                document.getElementById('loading-indicator').style.display = 'none';
            }
        }

        // Função para criar card do Pokémon
        function createPokemonCard(pokemon) {
            const card = document.createElement('a');
            card.href = `{{ route('pokemons.create') }}?pokemon_id=${pokemon.id}`;
            card.className = 'pokedex-card-national group relative p-6 cursor-pointer block';
            card.setAttribute('data-pokemon-id', pokemon.id);
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';

            const typesHtml = pokemon.types.map(type =>
                `<span class="type-badge rounded-full px-3 py-1 text-xs font-semibold text-white shadow-lg"
                 style="background-color: ${pokemon.type_colors[type] || '#777'}">
                 ${type.charAt(0).toUpperCase() + type.slice(1)}
                 </span>`
            ).join('');

            const weaknessesHtml = pokemon.weaknesses.length > 0
                ? pokemon.weaknesses.map(weakness =>
                    `<span class="weakness-badge rounded px-2 py-1 text-xs font-medium text-white shadow-sm"
                     style="background-color: ${getTypeColor(weakness)}">
                     ${weakness.charAt(0).toUpperCase() + weakness.slice(1)}
                     </span>`
                  ).join('')
                : '<span class="text-xs text-slate-400">Nenhuma fraqueza específica</span>';

            card.innerHTML = `
                <div class="mb-4 text-center">
                    <span class="mb-3 inline-block rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 px-3 py-1 text-xs font-bold text-white shadow-lg shadow-blue-500/30">
                        #${String(pokemon.id).padStart(3, '0')}
                    </span>
                    <h3 class="text-xl font-bold text-white">
                        ${pokemon.name}
                    </h3>
                </div>

                <div class="relative mb-4">
                    ${pokemon.sprite
                        ? `<div class="relative mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-800 shadow-lg shadow-slate-900/50 ring-2 ring-slate-600">
                            <div class="absolute inset-2 rounded-full bg-gradient-to-br from-slate-600 to-slate-700"></div>
                            <img src="${pokemon.sprite}" alt="${pokemon.name}" class="pokemon-sprite relative z-10 h-20 w-20 object-contain">
                           </div>`
                        : `<div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-slate-700">
                            <span class="text-3xl text-slate-500">?</span>
                           </div>`
                    }
                </div>

                <div class="mb-4 flex flex-wrap justify-center gap-2">
                    ${typesHtml}
                </div>

                <div>
                    <div class="mb-2 text-center text-sm font-semibold text-slate-300">Fraquezas</div>
                    <div class="flex flex-wrap justify-center gap-1">
                        ${weaknessesHtml}
                    </div>
                </div>
            `;

            return card;
        }

        // Detectar scroll para carregamento infinito (apenas quando não estiver pesquisando)
        function handleScroll() {
            if (isSearching) return;

            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;

            if (scrollTop + windowHeight >= documentHeight - 200) {
                loadMorePokemon();
            }
        }

        // Event listeners
        window.addEventListener('scroll', handleScroll);

        // Carregamento inicial quando a página estiver totalmente carregada
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar Pokémons iniciais
            const grid = document.getElementById('pokemon-grid');
            originalPokemons.forEach((pokemon, index) => {
                const card = createPokemonCard(pokemon);
                setTimeout(() => {
                    grid.appendChild(card);
                    card.offsetHeight;
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });

            console.log('Pokédex carregada com', currentOffset, 'Pokémon');
        });
  </script>
</x-app-layout>