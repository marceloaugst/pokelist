import { typeColor } from './TypeBadge';

const formatNumber = (id) => String(id).padStart(3, '0');

/**
 * Card da lista: fundo na cor do tipo primário, miniatura, número e nome.
 */
export default function PokemonCard({ pokemon, selected, onSelect }) {
    const primary = pokemon.types?.[0] ?? 'normal';
    const color = pokemon.type_colors?.[primary] ?? typeColor(primary);

    return (
        <button
            type="button"
            onClick={() => onSelect(pokemon)}
            className={`poke-card group relative flex flex-col items-center overflow-hidden rounded-2xl p-2 pb-1.5 text-white shadow-lg focus:outline-none ${
                selected ? 'is-selected' : ''
            }`}
            style={{
                background: `linear-gradient(160deg, ${color} 0%, ${color}cc 55%, ${color}99 100%)`,
            }}
            title={pokemon.name}
        >
            <span className="card-shine" />

            {/* meia pokébola decorativa */}
            <span className="pointer-events-none absolute -right-4 -top-4 h-16 w-16 rounded-full border-[6px] border-white/15" />

            <span className="self-start rounded-md bg-black/25 px-1.5 py-0.5 font-display text-[10px] font-bold tracking-widest">
                #{formatNumber(pokemon.id)}
            </span>

            {pokemon.sprite ? (
                <img
                    src={pokemon.sprite}
                    alt={pokemon.name}
                    loading="lazy"
                    className="h-16 w-16 object-contain drop-shadow-[0_4px_6px_rgba(0,0,0,0.35)] transition-transform duration-200 group-hover:scale-110 sm:h-20 sm:w-20"
                />
            ) : (
                <span className="flex h-16 w-16 items-center justify-center text-3xl sm:h-20 sm:w-20">?</span>
            )}

            <span className="w-full truncate text-center font-display text-[11px] font-bold uppercase tracking-wide sm:text-xs">
                {pokemon.name}
            </span>
        </button>
    );
}
