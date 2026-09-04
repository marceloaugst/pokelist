import { typeColor } from './TypeBadge';

/**
 * Multiplicador de dano de cada tipo atacante contra cada tipo defensor.
 * Só os valores diferentes de 1x estão listados.
 */
const ATTACK_CHART = {
    normal: { rock: 0.5, ghost: 0, steel: 0.5 },
    fire: { fire: 0.5, water: 0.5, grass: 2, ice: 2, bug: 2, rock: 0.5, dragon: 0.5, steel: 2 },
    water: { fire: 2, water: 0.5, grass: 0.5, ground: 2, rock: 2, dragon: 0.5 },
    electric: { water: 2, electric: 0.5, grass: 0.5, ground: 0, flying: 2, dragon: 0.5 },
    grass: {
        fire: 0.5, water: 2, grass: 0.5, poison: 0.5, ground: 2,
        flying: 0.5, bug: 0.5, rock: 2, dragon: 0.5, steel: 0.5,
    },
    ice: { fire: 0.5, water: 0.5, grass: 2, ice: 0.5, ground: 2, flying: 2, dragon: 2, steel: 0.5 },
    fighting: {
        normal: 2, ice: 2, poison: 0.5, flying: 0.5, psychic: 0.5, bug: 0.5,
        rock: 2, ghost: 0, dark: 2, steel: 2, fairy: 0.5,
    },
    poison: { grass: 2, poison: 0.5, ground: 0.5, rock: 0.5, ghost: 0.5, steel: 0, fairy: 2 },
    ground: { fire: 2, electric: 2, grass: 0.5, poison: 2, flying: 0, bug: 0.5, rock: 2, steel: 2 },
    flying: { electric: 0.5, grass: 2, fighting: 2, bug: 2, rock: 0.5, steel: 0.5 },
    psychic: { fighting: 2, poison: 2, psychic: 0.5, dark: 0, steel: 0.5 },
    bug: {
        fire: 0.5, grass: 2, fighting: 0.5, poison: 0.5, flying: 0.5, psychic: 2,
        ghost: 0.5, dark: 2, steel: 0.5, fairy: 0.5,
    },
    rock: { fire: 2, ice: 2, fighting: 0.5, ground: 0.5, flying: 2, bug: 2, steel: 0.5 },
    ghost: { normal: 0, psychic: 2, ghost: 2, dark: 0.5 },
    dragon: { dragon: 2, steel: 0.5, fairy: 0 },
    dark: { fighting: 0.5, psychic: 2, ghost: 2, dark: 0.5, fairy: 0.5 },
    steel: { fire: 0.5, water: 0.5, electric: 0.5, ice: 2, rock: 2, steel: 0.5, fairy: 2 },
    fairy: { fire: 0.5, fighting: 2, poison: 0.5, dragon: 2, dark: 2, steel: 0.5 },
};

const ALL_TYPES = Object.keys(ATTACK_CHART);

const ABBR = {
    normal: 'NOR', fire: 'FIR', water: 'WAT', electric: 'ELE', grass: 'GRA',
    ice: 'ICE', fighting: 'FIG', poison: 'POI', ground: 'GRO', flying: 'FLY',
    psychic: 'PSY', bug: 'BUG', rock: 'ROC', ghost: 'GHO', dragon: 'DRA',
    dark: 'DAR', steel: 'STE', fairy: 'FAI',
};

/** Rótulo curto para o multiplicador (1x fica vazio). */
const formatMultiplier = (value) => {
    if (value === 1) return '';
    if (value === 0) return '0';
    if (value === 0.25) return '¼';
    if (value === 0.5) return '½';
    return `${value}`;
};

/** Cor de fundo da célula: verde = fraqueza, vermelho = resistência. */
const cellColor = (value) => {
    if (value === 0) return '#3a3a3a';
    if (value === 0.25) return '#7a0000';
    if (value === 0.5) return '#b00000';
    if (value === 2) return '#4caf1e';
    if (value === 4) return '#1f7a00';
    return 'transparent';
};

/** Multiplicador total de cada tipo atacante contra a combinação de tipos. */
export const calculateDefenses = (types = []) => {
    const defenderTypes = types.filter((type) => ATTACK_CHART[type]);

    return ALL_TYPES.map((attacker) => ({
        type: attacker,
        multiplier: defenderTypes.reduce(
            (total, defender) => total * (ATTACK_CHART[attacker][defender] ?? 1),
            1
        ),
    }));
};

function TypeColumn({ type, multiplier }) {
    const label = formatMultiplier(multiplier);

    return (
        <div className="flex flex-col overflow-hidden rounded">
            <span
                className="px-1 py-1 text-center font-display text-[10px] font-bold uppercase tracking-wide text-white"
                style={{ backgroundColor: typeColor(type), textShadow: '0 1px 2px rgba(0,0,0,0.35)' }}
                title={type}
            >
                {ABBR[type]}
            </span>
            <span
                className="flex h-6 items-center justify-center text-[11px] font-bold text-white"
                style={{ backgroundColor: cellColor(multiplier) }}
            >
                {label}
            </span>
        </div>
    );
}

/**
 * Tabela de efetividade: quanto de dano cada tipo causa neste Pokémon.
 */
export default function TypeDefenses({ types = [], name }) {
    const defenses = calculateDefenses(types);

    if (defenses.length === 0) return null;

    return (
        <div className="space-y-3">
            <p className="text-xs text-dex-100/80">
                A efetividade de cada tipo contra{' '}
                <span className="font-semibold italic text-white">{name ?? 'este Pokémon'}</span>.
            </p>

            {[defenses.slice(0, 9), defenses.slice(9)].map((row, i) => (
                <div key={i} className="grid grid-cols-9 gap-1">
                    {row.map(({ type, multiplier }) => (
                        <TypeColumn key={type} type={type} multiplier={multiplier} />
                    ))}
                </div>
            ))}
        </div>
    );
}
