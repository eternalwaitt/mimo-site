/**
 * Dark Mode Toggle
 * Sistema de alternância entre modo claro e escuro
 * 
 * Features:
 * - Toggle button no header
 * - Persistência com localStorage
 * - Detecção de prefers-color-scheme
 * - Transições suaves
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.3.3
 */

(function() {
    'use strict';

    const THEME_KEY = 'mimo-theme';
    const DARK_THEME = 'dark';
    const LIGHT_THEME = 'light';

    /**
     * Aplica o tema ao documento
     */
    function applyTheme(theme) {
        const html = document.documentElement;
        if (theme === DARK_THEME) {
            html.setAttribute('data-theme', DARK_THEME);
        } else {
            html.removeAttribute('data-theme');
        }
        localStorage.setItem(THEME_KEY, theme);
    }

    /**
     * Obtém o tema preferido do usuário
     */
    function getPreferredTheme() {
        const stored = localStorage.getItem(THEME_KEY);
        if (stored) {
            return stored;
        }
        // Detectar prefers-color-scheme
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return DARK_THEME;
        }
        return LIGHT_THEME;
    }

    /**
     * Cria o botão toggle
     */
    function createToggleButton() {
        const button = document.createElement('button');
        button.className = 'dark-mode-toggle';
        button.setAttribute('aria-label', 'Alternar modo escuro');
        button.setAttribute('title', 'Alternar modo escuro');
        
        // Ícone SVG (lua/sol)
        button.innerHTML = `
            <svg class="dark-mode-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path class="icon-moon" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" style="display: block;"/>
                <circle class="icon-sun" cx="12" cy="12" r="5" style="display: none;"/>
                <line class="icon-sun" x1="12" y1="1" x2="12" y2="3" style="display: none;"/>
                <line class="icon-sun" x1="12" y1="21" x2="12" y2="23" style="display: none;"/>
                <line class="icon-sun" x1="4.22" y1="4.22" x2="5.64" y2="5.64" style="display: none;"/>
                <line class="icon-sun" x1="18.36" y1="18.36" x2="19.78" y2="19.78" style="display: none;"/>
                <line class="icon-sun" x1="1" y1="12" x2="3" y2="12" style="display: none;"/>
                <line class="icon-sun" x1="21" y1="12" x2="23" y2="12" style="display: none;"/>
                <line class="icon-sun" x1="4.22" y1="19.78" x2="5.64" y2="18.36" style="display: none;"/>
                <line class="icon-sun" x1="18.36" y1="5.64" x2="19.78" y2="4.22" style="display: none;"/>
            </svg>
        `;
        
        return button;
    }

    /**
     * Atualiza o ícone do botão baseado no tema atual
     */
    function updateToggleIcon(button, isDark) {
        const moon = button.querySelector('.icon-moon');
        const sun = button.querySelectorAll('.icon-sun');
        
        if (isDark) {
            moon.style.display = 'none';
            sun.forEach(icon => icon.style.display = 'block');
        } else {
            moon.style.display = 'block';
            sun.forEach(icon => icon.style.display = 'none');
        }
    }

    /**
     * Inicializa o dark mode
     */
    function initDarkMode() {
        const preferredTheme = getPreferredTheme();
        applyTheme(preferredTheme);
        
        // Adicionar botão toggle ao navbar
        const navbar = document.querySelector('.navbar .container');
        if (navbar) {
            const toggleButton = createToggleButton();
            const isDark = preferredTheme === DARK_THEME;
            updateToggleIcon(toggleButton, isDark);
            
            // Tentar encontrar navbar-nav (pode estar dentro de collapse ou direto)
            let nav = navbar.querySelector('.navbar-nav');
            
            // Se não encontrou, tentar dentro de navbar-collapse
            if (!nav) {
                const navbarCollapse = navbar.querySelector('.navbar-collapse');
                if (navbarCollapse) {
                    nav = navbarCollapse.querySelector('.navbar-nav');
                }
            }
            
            if (nav) {
                const li = document.createElement('li');
                li.className = 'nav-item ml-md-2 d-flex align-items-center';
                li.style.listStyle = 'none';
                li.appendChild(toggleButton);
                nav.appendChild(li);
            } else {
                // Fallback: adicionar direto no container se não encontrar nav
                console.warn('Navbar nav não encontrado, adicionando toggle no container');
                toggleButton.style.marginLeft = 'auto';
                toggleButton.style.display = 'inline-flex';
                navbar.appendChild(toggleButton);
            }
            
            // Event listener para toggle
            toggleButton.addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === DARK_THEME ? LIGHT_THEME : DARK_THEME;
                applyTheme(newTheme);
                updateToggleIcon(toggleButton, newTheme === DARK_THEME);
            });
        }
        
        // Escutar mudanças em prefers-color-scheme (se não houver preferência salva)
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', function(e) {
                // Só aplicar se não houver preferência salva
                if (!localStorage.getItem(THEME_KEY)) {
                    applyTheme(e.matches ? DARK_THEME : LIGHT_THEME);
                }
            });
        }
    }

    // Inicializar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDarkMode);
    } else {
        initDarkMode();
    }
})();

