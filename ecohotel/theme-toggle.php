<?php


function renderThemeToggle() {
    echo <<<HTML
    <button id="darkModeToggle" title="Cambiar tema">🌙</button>
    <script>

        function applyTheme(theme) {
            document.documentElement.classList.toggle('dark-mode', theme === 'dark');
            localStorage.setItem('theme', theme);
            document.getElementById('darkModeToggle').textContent = 
                theme === 'dark' ? '☀️' : '🌙';
        }
        

        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);
        

        document.getElementById('darkModeToggle').addEventListener('click', () => {
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    </script>
HTML;
}
?>