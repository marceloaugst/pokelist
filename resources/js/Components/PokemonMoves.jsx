import { useEffect, useState } from 'react';
import { typeColor } from './TypeBadge';

const TABS = [
    { key: 'level_up', label: 'Nível', hint: 'Movimentos aprendidos ao subir de nível' },
    { key: 'machine', label: 'MT/MO', hint: 'Movimentos aprendidos por máquina' },
    { key: 'egg', label: 'Ovo', hint: 'Movimentos herdados por reprodução' },
    { key: 'tutor', label: 'Tutor', hint: 'Movimentos ensinados por tutores' },
];

const CATEGORY_LABELS = {
    physical: 'Fís.',
    special: 'Esp.',
    status: 'Sta.',
};

const CATEGORY_COLORS = {
    physical: '#C92112',
    special: '#4F5870',
    status: '#8C888C',
};

const EMPTY_MOVES = { version_group: null, level_up: [], machine: [], egg: [], tutor: [] };

function CategoryTag({ category }) {
    return (
        <span
            className="inline-block rounded px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white"
            style={{ backgroundColor: CATEGORY_COLORS[category] ?? '#8C888C' }}
            title={category}
        >
            {CATEGORY_LABELS[category] ?? category}
        </span>
    );
}

function MoveTable({ moves, showLevel }) {
    if (moves.length === 0) {
        return (
            <p className="py-4 text-center text-xs text-dex-100/70">
                Nenhum movimento neste método.
            </p>
        );
    }

    return (
        <div className="dex-scroll max-h-64 overflow-y-auto rounded-lg bg-black/20">
            <table className="w-full text-left text-xs">
                <thead className="sticky top-0 z-10 bg-dex-900/95 backdrop-blur-sm">
                    <tr className="font-display uppercase tracking-wide text-yellow-300/90">
                        {showLevel && <th className="w-10 px-2 py-1.5 text-right">Lv.</th>}
                        <th className="px-2 py-1.5">Movimento</th>
                        <th className="px-2 py-1.5">Tipo</th>
                        <th className="px-2 py-1.5">Cat.</th>
                        <th className="w-10 px-2 py-1.5 text-right">Pot.</th>
                        <th className="w-10 px-2 py-1.5 text-right">Prec.</th>
                    </tr>
                </thead>
                <tbody>
                    {moves.map((move) => (
                        <tr key={move.slug} className="border-t border-white/10 hover:bg-white/5">
                            {showLevel && (
                                <td
                                    className="px-2 py-1.5 text-right font-semibold tabular-nums text-dex-100/90"
                                    title={move.level > 0 ? undefined : 'Aprendido ao evoluir'}
                                >
                                    {move.level > 0 ? move.level : 'Evo'}
                                </td>
                            )}
                            <td className="px-2 py-1.5 font-semibold">{move.name}</td>
                            <td className="px-2 py-1.5">
                                <span
                                    className="inline-block rounded px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white"
                                    style={{
                                        backgroundColor: typeColor(move.type),
                                        textShadow: '0 1px 2px rgba(0,0,0,0.35)',
                                    }}
                                >
                                    {move.type}
                                </span>
                            </td>
                            <td className="px-2 py-1.5">
                                <CategoryTag category={move.category} />
                            </td>
                            <td className="px-2 py-1.5 text-right tabular-nums text-dex-100/90">
                                {move.power ?? '—'}
                            </td>
                            <td className="px-2 py-1.5 text-right tabular-nums text-dex-100/90">
                                {move.accuracy ?? '∞'}
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}

/**
 * Movimentos do Pokémon separados por método de aprendizado.
 * Os dados chegam prontos da página, que os busca em paralelo com o detalhe.
 */
export default function PokemonMoves({ pokemonId, name, data, loading }) {
    const [tab, setTab] = useState('level_up');

    // Volta para a primeira aba ao trocar de Pokémon
    useEffect(() => setTab('level_up'), [pokemonId]);

    const moves = { ...EMPTY_MOVES, ...(data ?? {}) };

    if (loading) {
        return (
            <div className="flex items-center justify-center gap-3 py-6">
                <span
                    className="pokeball-watermark h-6 w-6 animate-spin"
                    style={{ animationDuration: '0.9s' }}
                />
                <span className="font-display text-[11px] font-bold uppercase tracking-[0.25em] text-white/80">
                    Carregando movimentos…
                </span>
            </div>
        );
    }

    if (!data) {
        return <p className="py-4 text-center text-xs text-dex-100/70">Não foi possível carregar os movimentos.</p>;
    }

    return (
        <div className="space-y-3">
            <p className="text-xs text-dex-100/80">
                Movimentos de <span className="font-semibold italic text-white">{name ?? 'este Pokémon'}</span>
                {moves.version_group && <> em <span className="font-semibold">{moves.version_group}</span></>}.
            </p>

            <div className="flex flex-wrap gap-1.5">
                {TABS.map(({ key, label, hint }) => (
                    <button
                        key={key}
                        type="button"
                        onClick={() => setTab(key)}
                        title={hint}
                        className={`rounded-full px-3 py-1 font-display text-[11px] font-bold uppercase tracking-wide transition ${
                            tab === key
                                ? 'bg-yellow-300 text-dex-900 shadow'
                                : 'bg-black/25 text-dex-100/80 hover:bg-black/40'
                        }`}
                    >
                        {label}
                        <span className="ml-1.5 opacity-70">{moves[key].length}</span>
                    </button>
                ))}
            </div>

            <MoveTable moves={moves[tab]} showLevel={tab === 'level_up'} />
        </div>
    );
}
