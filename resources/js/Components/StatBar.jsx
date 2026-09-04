const MAX_STAT = 255;

const barColor = (value) => {
    if (value < 50) return '#f34444';
    if (value < 80) return '#ffdd57';
    if (value < 110) return '#a0e515';
    return '#23cd5e';
};

export default function StatBar({ label, value }) {
    const width = Math.min(100, (value / MAX_STAT) * 100);

    return (
        <div className="flex items-center gap-2">
            <span className="w-20 shrink-0 text-right font-display text-[11px] font-semibold uppercase tracking-wide text-dex-100/90">
                {label}
            </span>
            <span className="w-9 shrink-0 text-right font-display text-sm font-bold">{value}</span>
            <div className="h-2.5 flex-1 overflow-hidden rounded-full bg-black/25">
                <div
                    className="h-full rounded-full transition-[width] duration-700 ease-out"
                    style={{ width: `${width}%`, backgroundColor: barColor(value) }}
                />
            </div>
        </div>
    );
}
