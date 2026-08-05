const TYPE_COLORS = {
    normal: '#A8A878',
    fire: '#F08030',
    water: '#6890F0',
    electric: '#F8D030',
    grass: '#78C850',
    ice: '#98D8D8',
    fighting: '#C03028',
    poison: '#A040A0',
    ground: '#E0C068',
    flying: '#A890F0',
    psychic: '#F85888',
    bug: '#A8B820',
    rock: '#B8A038',
    ghost: '#705898',
    dragon: '#7038F8',
    dark: '#705848',
    steel: '#B8B8D0',
    fairy: '#EE99AC',
};

export const typeColor = (type) => TYPE_COLORS[type] ?? '#777777';

export default function TypeBadge({ type, size = 'md' }) {
    const sizes = {
        sm: 'px-2 py-0.5 text-[10px]',
        md: 'px-3 py-1 text-xs',
        lg: 'px-4 py-1.5 text-sm',
    };

    return (
        <span
            className={`inline-block rounded font-display font-bold italic uppercase tracking-wider text-white shadow-sm ${sizes[size]}`}
            style={{
                backgroundColor: typeColor(type),
                textShadow: '0 1px 2px rgba(0,0,0,0.35)',
            }}
        >
            {type}
        </span>
    );
}
