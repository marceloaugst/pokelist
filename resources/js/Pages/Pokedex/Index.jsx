import { Head } from '@inertiajs/react';
import { useCallback, useEffect, useRef, useState } from 'react';
import PokemonCard from '../../Components/PokemonCard';
import PokemonDetail from '../../Components/PokemonDetail';

const BATCH_SIZE = 30;

const fetchJson = async (url) => {
    const response = await fetch(url, { headers: { Accept: 'application/json' } });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return response.json();
};

export default function Index({ total }) {
    const [pokemons, setPokemons] = useState([]);
    const [listLoading, setListLoading] = useState(false);
    const [query, setQuery] = useState('');
    const [results, setResults] = useState([]);
    const [searchLoading, setSearchLoading] = useState(false);
    const [selectedId, setSelectedId] = useState(null);
    const [detail, setDetail] = useState(null);
    const [detailLoading, setDetailLoading] = useState(false);
    const [moves, setMoves] = useState(null);
    const [movesLoading, setMovesLoading] = useState(false);

    const offsetRef = useRef(0);
    const loadingRef = useRef(false);
    const listRef = useRef(null);
    const detailRequestRef = useRef(0);
    const searchRequestRef = useRef(0);
    // Evita refazer as requisições ao voltar num Pokémon já visto
    const detailCacheRef = useRef(new Map());
    const movesCacheRef = useRef(new Map());

    const searching = query.trim() !== '';
    const visiblePokemons = searching ? results : pokemons;

    const loadMore = useCallback(async () => {
        if (loadingRef.current || offsetRef.current >= total) return;

        loadingRef.current = true;
        setListLoading(true);

        try {
            const response = await fetch(
                `/pokedex/list?offset=${offsetRef.current}&limit=${BATCH_SIZE}`,
                { headers: { Accept: 'application/json' } }
            );
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            offsetRef.current += BATCH_SIZE;
            setPokemons((prev) => [...prev, ...data.pokemons]);
        } catch (error) {
            console.error('Erro ao carregar a lista de Pokémon', error);
        } finally {
            loadingRef.current = false;
            setListLoading(false);
        }
    }, [total]);

    useEffect(() => {
        loadMore();
    }, [loadMore]);

    // Busca com debounce: cada tecla filtra os Pokémon cujo nome começa com o texto
    useEffect(() => {
        const term = query.trim();

        if (term === '') {
            setResults([]);
            setSearchLoading(false);
            searchRequestRef.current++;
            return;
        }

        setSearchLoading(true);
        const requestId = ++searchRequestRef.current;

        const timer = setTimeout(async () => {
            try {
                const response = await fetch(
                    `/pokedex/search?q=${encodeURIComponent(term)}`,
                    { headers: { Accept: 'application/json' } }
                );
                if (!response.ok) throw new Error(`HTTP ${response.status}`);

                const data = await response.json();

                if (requestId === searchRequestRef.current) {
                    setResults(data.pokemons);
                }
            } catch (error) {
                console.error('Erro ao buscar Pokémon', error);
                if (requestId === searchRequestRef.current) {
                    setResults([]);
                }
            } finally {
                if (requestId === searchRequestRef.current) {
                    setSearchLoading(false);
                }
            }
        }, 300);

        return () => clearTimeout(timer);
    }, [query]);

    const handleScroll = useCallback(() => {
        if (searching) return;

        const el = listRef.current;
        if (!el) return;

        if (el.scrollTop + el.clientHeight >= el.scrollHeight - 400) {
            loadMore();
        }
    }, [loadMore, searching]);

    const selectPokemon = useCallback(async (id) => {
        setSelectedId(id);

        // Ignora respostas antigas se o usuário clicou em outro Pokémon
        const requestId = ++detailRequestRef.current;
        const isCurrent = () => requestId === detailRequestRef.current;

        const cachedDetail = detailCacheRef.current.get(id);
        const cachedMoves = movesCacheRef.current.get(id);

        setDetail(cachedDetail ?? null);
        setDetailLoading(!cachedDetail);
        setMoves(cachedMoves ?? null);
        setMovesLoading(!cachedMoves);

        // As duas requisições saem juntas: os movimentos não esperam o detalhe
        const detailPromise = cachedDetail
            ? Promise.resolve(cachedDetail)
            : fetchJson(`/pokedex/${id}`);
        const movesPromise = cachedMoves
            ? Promise.resolve(cachedMoves)
            : fetchJson(`/pokedex/${id}/moves`);

        detailPromise
            .then((data) => {
                detailCacheRef.current.set(id, data);
                if (isCurrent()) setDetail(data);
            })
            .catch((error) => {
                console.error('Erro ao carregar detalhes do Pokémon', error);
                if (isCurrent()) setDetail(null);
            })
            .finally(() => {
                if (isCurrent()) setDetailLoading(false);
            });

        movesPromise
            .then((data) => {
                movesCacheRef.current.set(id, data);
                if (isCurrent()) setMoves(data);
            })
            .catch((error) => {
                console.error('Erro ao carregar movimentos do Pokémon', error);
                if (isCurrent()) setMoves(null);
            })
            .finally(() => {
                if (isCurrent()) setMovesLoading(false);
            });
    }, []);

    return (
        <>
            <Head title="Pokédex" />

            <div className="pokedex-bg relative flex h-screen flex-col overflow-hidden">
                <div className="pokedex-grid-overlay pointer-events-none absolute inset-0" />

                {/* Cabeçalho */}
                <header className="relative z-10 flex shrink-0 items-center gap-3 px-4 pb-2 pt-4 sm:px-6">
                    <div className="name-plate flex items-center gap-2 bg-gradient-to-r from-dex-800 via-dex-900 to-dex-800 px-6 py-2 shadow-lg">
                        <span className="pokeball-watermark h-5 w-5" />
                        <h1 className="font-display text-lg font-bold uppercase italic tracking-[0.25em] text-white">
                            Pokédex
                        </h1>
                    </div>
                    <div className="relative ml-auto w-full max-w-xs">
                        <input
                            type="search"
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            placeholder="Buscar Pokémon…"
                            aria-label="Buscar Pokémon pelo nome ou número"
                            className="w-full rounded-full border border-white/25 bg-white/15 py-2 pl-10 pr-4 font-display text-sm text-white placeholder-white/60 shadow-inner outline-none backdrop-blur-sm transition focus:border-white/50 focus:bg-white/25"
                        />
                        <svg
                            className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-white/70"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            strokeWidth="2"
                            strokeLinecap="round"
                        >
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                    </div>

                    <p className="hidden font-display text-xs uppercase tracking-widest text-white/70 sm:block">
                        {searching
                            ? `${results.length} encontrado(s)`
                            : `${pokemons.length} / ${total} Pokémon`}
                    </p>
                </header>

                {/* Conteúdo: detalhe à esquerda, lista à direita */}
                <main className="relative z-10 flex min-h-0 flex-1 flex-col gap-4 px-4 pb-4 sm:px-6 lg:flex-row">
                    <aside className="order-2 min-h-0 flex-1 lg:order-1 lg:max-w-[46%]">
                        <div className="h-full rounded-3xl bg-white/10 p-4 shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_20px_50px_-20px_rgba(0,40,60,0.6)] backdrop-blur-sm">
                            <PokemonDetail
                                pokemon={detail}
                                loading={detailLoading}
                                moves={moves}
                                movesLoading={movesLoading}
                                onSelectEvolution={selectPokemon}
                            />
                        </div>
                    </aside>

                    <section className="order-1 min-h-0 flex-1 lg:order-2">
                        <div
                            ref={listRef}
                            onScroll={handleScroll}
                            className="dex-scroll h-full overflow-y-auto rounded-3xl bg-dex-900/25 p-4 shadow-[inset_0_2px_10px_rgba(0,30,50,0.35)] backdrop-blur-sm"
                        >
                            <div className="grid grid-cols-3 gap-3 sm:grid-cols-4 xl:grid-cols-5">
                                {visiblePokemons.map((pokemon) => (
                                    <PokemonCard
                                        key={pokemon.id}
                                        pokemon={pokemon}
                                        selected={pokemon.id === selectedId}
                                        onSelect={(p) => selectPokemon(p.id)}
                                    />
                                ))}
                            </div>

                            {searching && !searchLoading && results.length === 0 && (
                                <p className="py-10 text-center font-display text-xs uppercase tracking-[0.25em] text-white/70">
                                    Nenhum Pokémon encontrado
                                </p>
                            )}

                            {(searching ? searchLoading : listLoading) && (
                                <div className="flex items-center justify-center gap-3 py-6">
                                    <span
                                        className="pokeball-watermark h-8 w-8 animate-spin"
                                        style={{ animationDuration: '0.9s' }}
                                    />
                                    <span className="font-display text-xs font-bold uppercase tracking-[0.3em] text-white/80">
                                        Carregando…
                                    </span>
                                </div>
                            )}
                        </div>
                    </section>
                </main>
            </div>
        </>
    );
}
