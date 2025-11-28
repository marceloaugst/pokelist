<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'pokeball-logo']) }}>
    <!-- Parte superior vermelha -->
    <path d="M50 5 A45 45 0 0 1 95 50 L65 50 A15 15 0 0 0 35 50 L5 50 A45 45 0 0 1 50 5" fill="#EF4444" />
    <!-- Parte inferior branca -->
    <path d="M50 95 A45 45 0 0 1 5 50 L35 50 A15 15 0 0 0 65 50 L95 50 A45 45 0 0 1 50 95" fill="#F9FAFB" />
    <!-- Linha central preta -->
    <rect x="5" y="48" width="90" height="4" fill="#1F2937" />
    <!-- Círculo central externo -->
    <circle cx="50" cy="50" r="18" fill="#1F2937" />
    <!-- Círculo central branco -->
    <circle cx="50" cy="50" r="12" fill="#F9FAFB" />
    <!-- Brilho -->
    <circle cx="50" cy="50" r="6" fill="#E5E7EB" />
    <circle cx="47" cy="47" r="3" fill="white" />
    <!-- Contorno -->
    <circle cx="50" cy="50" r="45" fill="none" stroke="#1F2937" stroke-width="3" />
</svg>
