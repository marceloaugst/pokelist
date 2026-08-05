import { Head } from '@inertiajs/react';
import { useCallback, useEffect, useRef, useState } from 'react';
import PokemonCard from '../../Components/PokemonCard';
import PokemonDetail from '../../Components/PokemonDetail';

const BATCH_SIZE = 30;

export default function Index({ total }) {
    const [pokemons, setPokemons] = useState([]);
    const [listLoading, setListLoading] = useState(false);
    const [selectedId, setSelectedId] = useState(null);
    const [detail, setDetail] = useState(null);
    const [detailLoading, setDetailLoading] = useState(false);

    const offsetRef = useRef(0);
    const loadingRef = useRef(false);
    const listRef = useRef(null);
    const detailRequestRef = useRef(0);

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

    const handleScroll = useCallback(() => {
        const el = listRef.current;
        if (!el) return;

        if (el.scrollTop + el.clientHeight >= el.scrollHeight - 400) {
            loadMore();
        }
    }, [loadMore]);

    const selectPokemon = useCallback(async (id) => {
        setSelectedId(id);
        setDetailLoading(true);

        const requestId = ++detailRequestRef.current;

        try {
            const response = await fetch(`/pokedex/${id}`, {
                headers: { Accept: 'application/json' },
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            // Ignora respostas antigas se o usuário clicou em outro Pokémon
            if (requestId === detailRequestRef.current) {
                setDetail(data);
            }
        } catch (error) {
            console.error('Erro ao carregar detalhes do Pokémon', error);
            if (requestId === detailRequestRef.current) {
                setDetail(null);
            }
        } finally {
            if (requestId === detailRequestRef.current) {
                setDetailLoading(false);
            }
        }
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
                    <p className="hidden font-display text-xs uppercase tracking-widest text-white/70 sm:block">
                        {pokemons.length} / {total} Pokémon
                    </p>
                </header>

                {/* Conteúdo: detalhe à esquerda, lista à direita */}
                <main className="relative z-10 flex min-h-0 flex-1 flex-col gap-4 px-4 pb-4 sm:px-6 lg:flex-row">
                    <aside className="order-2 min-h-0 flex-1 lg:order-1 lg:max-w-[46%]">
                        <div className="h-full rounded-3xl bg-white/10 p-4 shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_20px_50px_-20px_rgba(0,40,60,0.6)] backdrop-blur-sm">
                            <PokemonDetail
                                pokemon={detail}
                                loading={detailLoading}
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
                                {pokemons.map((pokemon) => (
                                    <PokemonCard
                                        key={pokemon.id}
                                        pokemon={pokemon}
                                        selected={pokemon.id === selectedId}
                                        onSelect={(p) => selectPokemon(p.id)}
                                    />
                                ))}
                            </div>

                            {listLoading && (
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
