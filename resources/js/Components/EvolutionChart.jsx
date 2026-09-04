/**
 * Cadeia de evolução em linha: sprites com a condição de evolução entre eles.
 * Clicar em um estágio seleciona aquele Pokémon.
 */
export default function EvolutionChart({ chain, currentId, onSelect }) {
    if (!chain || chain.length === 0) {
        return <p className="text-sm text-dex-100/80">Este Pokémon não evolui.</p>;
    }

    return (
        <div className="flex flex-wrap items-center justify-center gap-1">
            {chain.map((evo, index) => (
                <div key={`${evo.id}-${index}`} className="flex items-center">
                    {index > 0 && (
                        <div className="mx-1 flex w-16 flex-col items-center text-center">
                            <span className="text-lg leading-none text-yellow-300">➜</span>
                            {evo.evolution_details && (
                                <span className="mt-1 text-[10px] font-semibold leading-tight text-dex-100/90">
                                    {evo.evolution_details}
                                </span>
                            )}
                        </div>
                    )}

                    <button
                        type="button"
                        onClick={() => onSelect?.(evo.id)}
                        className={`group flex flex-col items-center rounded-xl px-2 py-1.5 transition hover:bg-white/10 focus:outline-none ${
                            evo.id === currentId ? 'bg-white/15 ring-2 ring-yellow-300' : ''
                        }`}
                        title={evo.name}
                    >
                        {evo.sprite ? (
                            <img
                                src={evo.sprite}
                                alt={evo.name}
                                loading="lazy"
                                className="h-16 w-16 object-contain drop-shadow-md transition-transform group-hover:scale-110"
                            />
                        ) : (
                            <span className="flex h-16 w-16 items-center justify-center text-2xl">?</span>
                        )}
                        <span className="mt-0.5 font-display text-[11px] font-bold uppercase tracking-wide">
                            {evo.name}
                        </span>
                        <span className="text-[9px] text-dex-100/70">#{String(evo.id).padStart(3, '0')}</span>
                    </button>
                </div>
            ))}
        </div>
    );
}
