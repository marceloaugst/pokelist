import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Pokédex Manager - JavaScript Enhancements

document.addEventListener("DOMContentLoaded", function () {
    // Adiciona efeitos de hover suaves aos cards de Pokémon
    const pokedexCards = document.querySelectorAll(".pokedex-card");
    pokedexCards.forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-8px) scale(1.02)";
        });
        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0) scale(1)";
        });
    });

    // Animação de entrada para os cards (fade in sequencial)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll(
        ".pokedex-card, .stat-input-group"
    );
    animatedElements.forEach((el) => {
        el.style.opacity = "0";
        el.style.transform = "translateY(20px)";
        el.style.transition = "opacity 0.5s ease, transform 0.5s ease";
        observer.observe(el);
    });

    // Efeito de brilho no logo da Pokébola
    const logo = document.querySelector(".pokeball-logo");
    if (logo) {
        logo.addEventListener("click", function () {
            this.classList.add("animate-spin");
            setTimeout(() => {
                this.classList.remove("animate-spin");
            }, 1000);
        });
    }

    // Smooth scroll para navegação
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });

    // Efeito de partículas flutuantes no background
    createFloatingParticles();
});

// Cria partículas flutuantes estilo Pokémon
function createFloatingParticles() {
    const container = document.body;
    const particleCount = 15;
    const colors = ["#EF4444", "#FBBF24", "#3B82F6", "#10B981", "#8B5CF6"];

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement("div");
        particle.className = "floating-particle";
        particle.style.cssText = `
            position: fixed;
            width: ${Math.random() * 8 + 4}px;
            height: ${Math.random() * 8 + 4}px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            border-radius: 50%;
            pointer-events: none;
            opacity: 0.15;
            z-index: 0;
            left: ${Math.random() * 100}vw;
            top: ${Math.random() * 100}vh;
            animation: float ${Math.random() * 10 + 10}s infinite ease-in-out;
            animation-delay: ${Math.random() * 5}s;
        `;
        container.appendChild(particle);
    }

    // Adiciona keyframes para a animação
    if (!document.querySelector("#particle-animation")) {
        const style = document.createElement("style");
        style.id = "particle-animation";
        style.textContent = `
            @keyframes float {
                0%, 100% {
                    transform: translateY(0) translateX(0) scale(1);
                    opacity: 0.15;
                }
                25% {
                    transform: translateY(-30px) translateX(15px) scale(1.1);
                    opacity: 0.25;
                }
                50% {
                    transform: translateY(-15px) translateX(-10px) scale(0.9);
                    opacity: 0.1;
                }
                75% {
                    transform: translateY(-40px) translateX(20px) scale(1.05);
                    opacity: 0.2;
                }
            }

            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .animate-spin {
                animation: spin 0.5s ease-in-out;
            }
        `;
        document.head.appendChild(style);
    }
}

// Função utilitária para copiar texto
window.copyToClipboard = function (text) {
    navigator.clipboard.writeText(text).then(() => {
        // Mostra feedback visual
        const toast = document.createElement("div");
        toast.className =
            "fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in";
        toast.textContent = "✓ Copiado!";
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
};
