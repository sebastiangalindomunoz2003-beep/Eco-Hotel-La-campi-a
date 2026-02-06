document.addEventListener('DOMContentLoaded', () => {
    // Scroll suave
    document.querySelectorAll('nav a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const destino = document.querySelector(this.getAttribute('href'));
            if (destino) destino.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Efecto hover en cards
    const cards = document.querySelectorAll('.room-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => card.classList.add('hovered'));
        card.addEventListener('mouseleave', () => card.classList.remove('hovered'));
    });

    // Validación formulario contacto
    const form = document.querySelector('#contactForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombre = form.querySelector('[name="nombre"]');
            const mensaje = form.querySelector('[name="mensaje"]');
            if (!nombre.value.trim() || !mensaje.value.trim()) {
                alert('Por favor, completa todos los campos obligatorios.');
                e.preventDefault();
            }
        });
    }

    // Botón volver arriba (parte superior centrada)
    const btnTop = document.createElement('button');
    btnTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
    btnTop.id = 'btnTop';
    btnTop.title = 'Volver arriba';
    btnTop.style.cssText = `
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #daa520;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
        cursor: pointer;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        transition: background-color 0.3s ease, opacity 0.3s ease;
        opacity: 0;
    `;
    document.body.appendChild(btnTop);

    btnTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            btnTop.style.display = 'block';
            setTimeout(() => btnTop.style.opacity = '1', 10);
        } else {
            btnTop.style.opacity = '0';
            setTimeout(() => btnTop.style.display = 'none', 300);
        }
    });
    
    // Actualizar año en footer
    document.getElementById("year").textContent = new Date().getFullYear();

    // Control de música de fondo
    const musicPlayer = () => {
        const music = document.getElementById('backgroundMusic');
        const toggleBtn = document.getElementById('toggleMusic');
        const volumeControl = document.getElementById('volumeControl');
        let isPlaying = false;

        // Configuración inicial
        music.volume = volumeControl.value;
        
        // Guardar preferencia del usuario
        const savedVolume = localStorage.getItem('musicVolume');
        const savedState = localStorage.getItem('musicState');
        
        if (savedVolume !== null) {
            music.volume = savedVolume;
            volumeControl.value = savedVolume;
        }
        
        if (savedState === 'playing') {
            toggleMusic();
        }

        // Control de volumen
        volumeControl.addEventListener('input', function() {
            music.volume = this.value;
            localStorage.setItem('musicVolume', this.value);
        });

        // Función para alternar música
        function toggleMusic() {
            if (isPlaying) {
                music.pause();
                toggleBtn.innerHTML = '<i class="fas fa-music"></i> <span>Música: OFF</span>';
                localStorage.setItem('musicState', 'paused');
            } else {
                music.play().then(() => {
                    toggleBtn.innerHTML = '<i class="fas fa-music"></i> <span>Música: ON</span>';
                    localStorage.setItem('musicState', 'playing');
                }).catch(e => {
                    // Si el autoplay está bloqueado, mostrar instrucción
                    toggleBtn.innerHTML = '<i class="fas fa-music"></i> <span>Click para activar</span>';
                    console.log("Autoplay bloqueado:", e);
                });
            }
            isPlaying = !isPlaying;
        }

        // Control de reproducción
        toggleBtn.addEventListener('click', toggleMusic);

        // Pausar música cuando la página no está visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && isPlaying) {
                music.pause();
            } else if (!document.hidden && isPlaying && music.paused) {
                music.play();
            }
        });
    };

    // Inicializar el reproductor
    musicPlayer();
});