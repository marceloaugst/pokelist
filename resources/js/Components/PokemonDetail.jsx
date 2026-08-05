import EvolutionChart from './EvolutionChart';
import StatBar from './StatBar';
import TypeBadge from './TypeBadge';

const formatNumber = (id) => String(id).padStart(3, '0');

function Section({ title, children }) {
    return (
        <section className="animate-fade-up rounded-2xl bg-dex-900/40 p-4 backdrop-blur-sm">
            <h3 className="mb-3 border-b-2 border-yellow-300/70 pb-1 font-display text-sm font-bold uppercase italic tracking-widest text-yellow-300">
                {title}
            </h3>
            {children}
        </section>
    );
}

function EmptyState() {
    return (
        <div className="flex h-full flex-col items-center justify-center gap-6 text-center">
            <div className="pokeball-watermark h-40 w-40 animate-spin-slow opacity-70" />
            <div>
                <p className="font-display text-xl font-bold uppercase italic tracking-widest text-white/90">
                    Nenhum Pokémon selecionado
                </p>
                <p className="mt-2 text-sm text-dex-100/80">
                    Escolha um Pokémon na lista ao lado para ver os detalhes.
                </p>
            </div>
        </div>
    );
}

function LoadingState() {
    return (
        <div className="flex h-full flex-col items-center justify-center gap-5">
            <div className="pokeball-watermark h-28 w-28 animate-spin opacity-90" style={{ animationDuration: '0.9s' }} />
            <p className="font-display text-sm font-bold uppercase tracking-[0.3em] text-white/90">Carregando…</p>
        </div>
    );
}

const STAT_LABELS = [
    ['hp', 'HP'],
    ['attack', 'Attack'],
    ['defense', 'Defense'],
    ['sp_attack', 'Sp. Atk'],
    ['sp_defense', 'Sp. Def'],
    ['speed', 'Speed'],
];

/**
 * Painel esquerdo: artwork grande, nome, tipos, Abilities, Species,
 * Base Stats, Evolution chart e Mega Evolutions (quando houver).
 */
export default function PokemonDetail({ pokemon, loading, onSelectEvolution }) {
    if (loading) return <LoadingState />;
    if (!pokemon) return <EmptyState />;

    const statTotal = pokemon.stats
        ? Object.values(pokemon.stats).reduce((sum, v) => sum + v, 0)
        : 0;

    return (
        <div key={pokemon.id} className="flex h-full flex-col">
            {/* Artwork */}
            <div className="relative flex shrink-0 items-center justify-center pb-2 pt-4">
                <div className="pokeball-watermark absolute h-64 w-64 opacity-60" />
                {pokemon.sprite ? (
                    <img
                        src={pokemon.sprite}
                        alt={pokemon.name}
                        className="animate-pop-in relative z-10 h-56 w-56 object-contain drop-shadow-[0_18px_25px_rgba(0,30,50,0.45)] sm:h-64 sm:w-64"
                    />
                ) : (
                    <span className="relative z-10 text-7xl">?</span>
                )}
            </div>

            {/* Placa com nome e número */}
            <div className="relative z-10 mx-auto -mt-1 w-64 shrink-0">
                <div className="name-plate bg-gradient-to-r from-dex-800 via-dex-900 to-dex-800 px-6 py-2 text-center shadow-lg">
                    <span className="mr-2 font-display text-xs font-bold text-yellow-300">
                        #{formatNumber(pokemon.id)}
                    </span>
                    <span className="font-display text-lg font-bold uppercase italic tracking-wider">
                        {pokemon.name}
                    </span>
                </div>
            </div>

            {/* Tipos */}
            <div className="mt-3 flex shrink-0 justify-center gap-2">
                {(pokemon.types ?? []).map((type) => (
                    <TypeBadge key={type} type={type} size="lg" />
                ))}
            </div>

            {/* Seções roláveis */}
            <div className="dex-scroll mt-4 flex-1 space-y-4 overflow-y-auto pb-4 pr-1">
                <Section title="Species">
                    <dl className="grid grid-cols-2 gap-x-4 gap-y-2 text-sm sm:grid-cols-3">
                        <div>
                            <dt className="text-[11px] uppercase tracking-wide text-dex-100/70">Espécie</dt>
                            <dd className="font-semibold">{pokemon.species ?? '—'}</dd>
                        </div>
                        <div>
                            <dt className="text-[11px] uppercase tracking-wide text-dex-100/70">Altura</dt>
                            <dd className="font-semibold">{pokemon.height != null ? `${pokemon.height} m` : '—'}</dd>
                        </div>
                        <div>
                            <dt className="text-[11px] uppercase tracking-wide text-dex-100/70">Peso</dt>
                            <dd className="font-semibold">{pokemon.weight != null ? `${pokemon.weight} kg` : '—'}</dd>
                        </div>
                    </dl>
                </Section>

                <Section title="Abilities">
                    <ul className="space-y-1.5">
                        {(pokemon.abilities ?? []).map((ability, i) => (
                            <li key={ability.name} className="flex items-center gap-2 text-sm">
                                <span className="font-display text-xs font-bold text-yellow-300">{i + 1}.</span>
                                <span className="font-semibold">{ability.name}</span>
                                {ability.is_hidden && (
                                    <span className="rounded bg-black/30 px-1.5 py-0.5 text-[10px] uppercase tracking-wider text-dex-100/80">
                                        hidden
                                    </span>
                                )}
                            </li>
                        ))}
                        {(!pokemon.abilities || pokemon.abilities.length === 0) && (
                            <li className="text-sm text-dex-100/80">—</li>
                        )}
                    </ul>
                </Section>

                <Section title="Base Stats">
                    <div className="space-y-2">
                        {STAT_LABELS.map(([key, label]) => (
                            <StatBar key={key} label={label} value={pokemon.stats?.[key] ?? 0} />
                        ))}
                        <div className="flex items-center gap-2 border-t border-white/15 pt-2">
                            <span className="w-20 shrink-0 text-right font-display text-[11px] font-bold uppercase tracking-wide text-yellow-300">
                                Total
                            </span>
                            <span className="w-9 shrink-0 text-right font-display text-sm font-bold text-yellow-300">
                                {statTotal}
                            </span>
                        </div>
                    </div>
                </Section>

                <Section title="Evolution Chart">
                    <EvolutionChart
                        chain={pokemon.evolution_chain}
                        currentId={pokemon.id}
                        onSelect={onSelectEvolution}
                    />
                </Section>

                {pokemon.mega_evolutions?.length > 0 && (
                    <Section title="Mega Evolution">
                        <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            {pokemon.mega_evolutions.map((mega) => (
                                <div
                                    key={mega.form}
                                    className="flex items-center gap-3 rounded-xl bg-black/25 p-3"
                                >
                                    {mega.sprite ? (
                                        <img
                                            src={mega.sprite}
                                            alt={mega.name}
                                            loading="lazy"
                                            className="h-20 w-20 shrink-0 animate-float object-contain drop-shadow-lg"
                                        />
                                    ) : (
                                        <span className="flex h-20 w-20 items-center justify-center text-3xl">?</span>
                                    )}
                                    <div className="min-w-0">
                                        <p className="truncate font-display text-sm font-bold uppercase italic tracking-wide">
                                            {mega.name}
                                        </p>
                                        <div className="mt-1.5 flex flex-wrap gap-1">
                                            {(mega.types ?? []).map((type) => (
                                                <TypeBadge key={type} type={type} size="sm" />
                                            ))}
                                        </div>
                                        {mega.stats && (
                                            <p className="mt-1.5 text-[11px] text-dex-100/80">
                                                Total:{' '}
                                                <span className="font-bold text-yellow-300">
                                                    {Object.values(mega.stats).reduce((s, v) => s + v, 0)}
                                                </span>
                                            </p>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </Section>
                )}
            </div>
        </div>
    );
}
