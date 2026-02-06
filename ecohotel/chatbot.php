<?php
// chatbot.php - Asistente virtual mejorado para EcoHotel con modo oscuro

$faq = [
    "habitacion" => [
        "title" => "🛏️ Habitación y lavandería",
        "content" => "
        - Ropa de cama: Ropa de cama de algodón<br>
        - Entretenimiento: TV<br>
        - Calefacción y refrigeración: Ventiladores portables<br>
        - Seguridad: Extintor de incendios, Botiquín
        ",
        "keywords" => ["habitacion", "habitación", "cuarto", "dormitorio", "alojamiento", "hospedaje", "cama", "ropa cama", "sábanas", "almohadas", "lavanderia", "lavandería", "dormir", "descanso"]
    ],
    "seguridad" => [
        "title" => "🛡️ Seguridad en el hogar",
        "content" => "
        - Cámaras de seguridad exteriores en la propiedad (registran pasillos alrededor de la casa)<br>
        - Extintor de incendios<br>
        - Botiquín<br>
        ",
        "keywords" => ["seguridad", "proteccion", "protección", "camaras", "cámaras", "vigilancia", "extintor", "incendio", "botiquin", "botiquín", "primeros auxilios", "emergencia", "detector humo", "monoxido", "monóxido", "seguro", "protegido"]
    ],
    "internet" => [
        "title" => "🌐 Internet y oficina",
        "content" => "
        - Wifi disponible<br>
        - Área para trabajar
        ",
        "keywords" => ["internet", "wifi", "conexion", "conexión", "red", "oficina", "trabajar", "teletrabajo", "computador", "ordenador", "laptop", "zoom", "reuniones", "conectarse", "redes"]
    ],
    "cocina" => [
        "title" => "🍳 Cocina y comedor",
        "content" => "
        - Cocina disponible para huéspedes<br>
        - Artículos básicos: Ollas, sartenes, aceite, sal y pimienta<br>
        - Mini refrigerador<br>
        - Comedor al aire libre
        ",
        "keywords" => ["cocina", "cocinar", "comedor", "comer", "alimentos", "comida", "olla", "sarten", "sartén", "utensilios", "refrigerador", "nevera", "heladera", "microondas", "horno", "cubiertos", "vajilla", "cocinar", "alimentacion", "alimentación"]
    ],
    "estacionamiento" => [
        "title" => "🚗 Estacionamiento e instalaciones",
        "content" => "Estacionamiento gratuito en las instalaciones",
        "keywords" => ["estacionamiento", "parking", "aparcamiento", "auto", "carro", "vehiculo", "vehículo", "moto", "parqueadero", "garaje", "aparcar", "estacionar", "lugar auto"]
    ],
    "servicios" => [
        "title" => "🛎 Servicios",
        "content" => "
        - Se permite dejar equipaje<br>
        - Opción de llegar pronto o salir tarde<br>
        - Apto para fumadores<br>
        - Llegada autónoma<br>
        - Personal disponible 24 horas para recibir huéspedes
        ",
        "keywords" => ["servicios", "equipaje", "maletas", "llegada", "salida", "horario", "fumador", "no fumador", "recepcion", "recepción", "personal", "ayuda", "asistencia", "checkin", "check-out", "llegar", "salir", "horarios"]
    ],
    "no incluidos" => [
        "title" => "❌ No incluidos",
        "content" => "
        - No disponible: Lavadora, Secadora<br>
        - No disponible: Calefacción<br>
        - No disponible: Agua caliente
        ",
        "keywords" => ["no incluido", "faltante", "disponible", "incluye", "servicios", "lavadora", "secadora", "detector humo", "detector monoxido", "calefaccion", "calefacción", "agua caliente", "ducha", "baño", "incluido", "tiene"]
    ],
    "reglas" => [
        "title" => "📜 Reglas de la casa",
        "content" => "
        - Llegada: a partir de las 2:00 p.m.<br>
        - Salida: antes de las 2:00 p.m.<br>
        - No se admiten mascotas
        ",
        "keywords" => ["reglas", "normas", "politicas", "políticas", "horario", "llegada", "salida", "checkin", "check-out", "mascotas", "animales", "perro", "gato", "prohibido", "permite", "hora entrada", "hora salida"]
    ],
    "capacidad" => [
        "title" => "👥 Capacidad",
        "content" => "
        - Precio base incluye 10 personas<br>
        - Capacidad máxima: 20 personas<br>
        - Personas adicionales tienen costo extra
        ",
        "keywords" => ["capacidad", "personas", "huespedes", "huéspedes", "invitados", "grupo", "precio", "costo", "tarifa", "adicional", "maximo", "máximo", "ocupacion", "ocupación", "gente", "cupo"]
    ],
    "descripcion" => [
        "title" => "✨ Descripción",
        "content" => "Desconecta de tus preocupaciones en este espacio tan amplio y sereno.",
        "keywords" => ["descripcion", "descripción", "info", "informacion", "información", "general", "caracteristicas", "características", "espacio", "lugar", "ambiente", "hotel", "ecohotel", "sobre"]
    ]
];

// Función para normalizar texto (quitar acentos y convertir a minúsculas)
function normalizeText($text) {
    $text = mb_strtolower($text, 'UTF-8');
    $text = str_replace(
        ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
        ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
        $text
    );
    return $text;
}
?>

<style>
    /* Variables CSS para modo claro/oscuro */
    :root {
        --chatbot-bg: #ffffff;
        --chatbot-text: #333333;
        --chatbot-card-bg: #f8f9fa;
        --chatbot-border: #e9ecef;
        --chatbot-primary: #2e8b57;
        --chatbot-primary-hover: #3cb371;
        --chatbot-header-bg: linear-gradient(135deg, #2e8b57, #3cb371);
        --chatbot-menu-bg: #f8f9fa;
        --chatbot-quick-bg: #f1f3f5;
        --chatbot-shadow: 0 10px 30px rgba(0,0,0,0.15);
        --chatbot-scrollbar: #c1c1c1;
        --chatbot-scrollbar-track: #f1f1f1;
    }

    /* Modo oscuro - se activa si el body tiene clase dark-mode */
    body.dark-mode {
        --chatbot-bg: #1e1e1e;
        --chatbot-text: #e0e0e0;
        --chatbot-card-bg: #2d2d2d;
        --chatbot-border: #3d3d3d;
        --chatbot-primary: #3cb371;
        --chatbot-primary-hover: #2e8b57;
        --chatbot-header-bg: linear-gradient(135deg, #1a3a27, #2e8b57);
        --chatbot-menu-bg: #252525;
        --chatbot-quick-bg: #333333;
        --chatbot-shadow: 0 10px 30px rgba(0,0,0,0.3);
        --chatbot-scrollbar: #555555;
        --chatbot-scrollbar-track: #333333;
    }

    /* Estilos base del chatbot */
    #ecohotel-chatbot {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    /* Botón de toggle */
    #chatbot-toggle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: none;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--chatbot-header-bg);
        color: white;
        position: fixed;
        bottom: 25px;
        right: 25px;
        flex-direction: column;
        padding: 10px;
        text-align: center;
    }
    
    #chatbot-toggle:hover {
        transform: scale(1.1) translateY(-5px);
        box-shadow: 0 6px 20px rgba(46, 139, 87, 0.3);
        background: linear-gradient(135deg, var(--chatbot-primary-hover), var(--chatbot-primary));
    }
    
    #chatbot-toggle i {
        font-size: 24px;
        margin-bottom: 5px;
    }
    
    /* Contenedor principal del chat */
    #chatbot-container {
        display: none;
        position: fixed;
        bottom: 110px;
        right: 25px;
        width: 450px;
        max-width: 90vw;
        height: 600px;
        max-height: 70vh;
        background: var(--chatbot-bg);
        border-radius: 16px;
        box-shadow: var(--chatbot-shadow);
        overflow: hidden;
        z-index: 1001;
        transition: all 0.3s ease;
        border: none;
        transform: translateY(10px);
        opacity: 0;
    }
    
    #chatbot-container.show {
        display: flex;
        flex-direction: column;
        transform: translateY(0);
        opacity: 1;
    }
    
    /* Header del chat */
    .chatbot-header {
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--chatbot-header-bg);
        color: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .chatbot-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    #chatbot-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    
    #chatbot-close:hover {
        background: rgba(255,255,255,0.3);
    }
    
    /* Contenedor principal del contenido */
    .chatbot-content {
        display: flex;
        flex: 1;
        overflow: hidden;
    }
    
    /* Menú lateral */
    .chatbot-menu {
        width: 150px;
        background: var(--chatbot-menu-bg);
        border-right: 1px solid var(--chatbot-border);
        overflow-y: auto;
        padding: 10px 0;
    }
    
    .menu-item {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
        border-left: 3px solid transparent;
        color: var(--chatbot-text);
    }
    
    .menu-item:hover {
        background: var(--chatbot-border);
        border-left: 3px solid var(--chatbot-primary);
    }
    
    .menu-item.active {
        background: var(--chatbot-border);
        border-left: 3px solid var(--chatbot-primary);
        font-weight: 500;
    }
    
    /* Área de mensajes */
    #chatbot-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: var(--chatbot-bg);
        scroll-behavior: smooth;
    }
    
    /* Estilos para los mensajes */
    .bot-message {
        background: var(--chatbot-card-bg);
        color: var(--chatbot-text);
        padding: 12px 16px;
        border-radius: 18px 18px 18px 4px;
        margin-bottom: 12px;
        max-width: 85%;
        font-size: 14px;
        line-height: 1.5;
        animation: fadeIn 0.3s ease;
        border: 1px solid var(--chatbot-border);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .user-message {
        background: linear-gradient(135deg, var(--chatbot-primary), var(--chatbot-primary-hover));
        color: white;
        padding: 12px 16px;
        border-radius: 18px 18px 4px 18px;
        margin-bottom: 12px;
        max-width: 85%;
        margin-left: auto;
        font-size: 14px;
        line-height: 1.5;
        animation: fadeIn 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Preguntas rápidas */
    .quick-questions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 15px;
        background: var(--chatbot-quick-bg);
        border-top: 1px solid var(--chatbot-border);
    }
    
    .quick-question {
        background: var(--chatbot-bg);
        border: 1px solid var(--chatbot-primary);
        color: var(--chatbot-primary);
        border-radius: 20px;
        padding: 8px 15px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s ease;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .quick-question:hover {
        background: var(--chatbot-primary);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Área de entrada */
    .chatbot-input {
        display: flex;
        padding: 15px;
        background: var(--chatbot-bg);
        border-top: 1px solid var(--chatbot-border);
    }
    
    #chatbot-user-input {
        flex: 1;
        padding: 12px 18px;
        border: 1px solid var(--chatbot-border);
        border-radius: 25px;
        outline: none;
        font-size: 14px;
        background: var(--chatbot-card-bg);
        color: var(--chatbot-text);
        transition: all 0.2s;
    }
    
    #chatbot-user-input:focus {
        border-color: var(--chatbot-primary);
        box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.1);
    }
    
    #chatbot-send {
        background: linear-gradient(135deg, var(--chatbot-primary), var(--chatbot-primary-hover));
        color: white;
        border: none;
        border-radius: 25px;
        padding: 0 20px;
        margin-left: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 80px;
    }
    
    #chatbot-send:hover {
        background: linear-gradient(135deg, var(--chatbot-primary-hover), var(--chatbot-primary));
        transform: translateY(-1px);
    }
    
    /* Mensaje de bienvenida */
    .welcome-message {
        text-align: center;
        padding: 20px;
        color: var(--chatbot-text);
    }
    
    .welcome-message h4 {
        margin-top: 0;
        color: var(--chatbot-primary);
    }
    
    .welcome-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 15px;
    }
    
    .welcome-option {
        background: var(--chatbot-card-bg);
        border: 1px solid var(--chatbot-border);
        border-radius: 8px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
        color: var(--chatbot-text);
    }
    
    .welcome-option:hover {
        background: var(--chatbot-border);
        border-color: var(--chatbot-primary);
    }
    
    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Personalización del scrollbar */
    #chatbot-messages::-webkit-scrollbar,
    .chatbot-menu::-webkit-scrollbar {
        width: 6px;
    }
    
    #chatbot-messages::-webkit-scrollbar-track,
    .chatbot-menu::-webkit-scrollbar-track {
        background: var(--chatbot-scrollbar-track);
        border-radius: 10px;
    }
    
    #chatbot-messages::-webkit-scrollbar-thumb,
    .chatbot-menu::-webkit-scrollbar-thumb {
        background: var(--chatbot-scrollbar);
        border-radius: 10px;
    }
    
    #chatbot-messages::-webkit-scrollbar-thumb:hover,
    .chatbot-menu::-webkit-scrollbar-thumb:hover {
        background: var(--chatbot-primary);
    }
    
    /* Estilos responsivos */
    @media screen and (max-width: 768px) {
        #chatbot-container {
            width: 90vw;
            right: 5vw;
            bottom: 80px;
            height: 70vh;
            border-radius: 12px;
        }
        
        #chatbot-toggle {
            width: 60px;
            height: 60px;
            bottom: 15px;
            right: 15px;
            font-size: 12px;
        }
        
        #chatbot-toggle i {
            font-size: 20px;
        }
        
        .quick-questions {
            gap: 6px;
            padding: 12px;
        }
        
        .quick-question {
            padding: 6px 12px;
            font-size: 11px;
        }
        
        #chatbot-messages {
            padding: 15px;
        }
        
        .chatbot-menu {
            width: 120px;
        }
        
        .menu-item {
            padding: 8px 10px;
            font-size: 12px;
        }
        
        .welcome-options {
            grid-template-columns: 1fr;
        }
    }
    
    @media screen and (max-width: 480px) {
        #chatbot-container {
            height: 75vh;
            bottom: 70px;
        }
        
        .chatbot-header {
            padding: 12px 15px;
        }
        
        #chatbot-messages {
            padding: 12px;
        }
        
        .bot-message, .user-message {
            padding: 10px 14px;
            font-size: 13px;
            max-width: 90%;
        }
        
        .chatbot-input {
            padding: 12px;
        }
        
        #chatbot-user-input {
            padding: 10px 15px;
        }
        
        .chatbot-menu {
            display: none;
        }
    }
</style>

<div id="ecohotel-chatbot">
    <button id="chatbot-toggle" aria-label="Abrir asistente virtual">
        <i class="fas fa-comments"></i>
        <span>Asistente</span>
    </button>
    
    <div id="chatbot-container">
        <div class="chatbot-header">
            <h3><i class="fas fa-leaf"></i> Asistente EcoHotel</h3>
            <button id="chatbot-close" aria-label="Cerrar chat">×</button>
        </div>
        
        <div class="chatbot-content">
            <div class="chatbot-menu">
                <?php foreach($faq as $key => $item): ?>
                    <div class="menu-item" onclick="sendQuickQuestion('<?php echo $key; ?>')">
                        <?php echo $item['title']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div id="chatbot-messages">
                <div class="welcome-message">
                    <h4>¡Bienvenido al EcoHotel! 🌿</h4>
                    <p>¿En qué puedo ayudarte hoy? Selecciona una opción:</p>
                    
                    <div class="welcome-options">
                        <?php 
                        // Mostrar las primeras 6 categorías como opciones principales
                        $count = 0;
                        foreach($faq as $key => $item): 
                            if($count < 6):
                        ?>
                            <div class="welcome-option" onclick="sendQuickQuestion('<?php echo $key; ?>')">
                                <?php echo $item['title']; ?>
                            </div>
                        <?php 
                            $count++;
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="quick-questions">
            <?php 
            // Mostrar 4 preguntas rápidas en la parte inferior
            $quick_questions = array_slice($faq, 0, 4);
            foreach($quick_questions as $key => $item): 
            ?>
                <button class="quick-question" onclick="sendQuickQuestion('<?php echo $key; ?>')">
                    <?php echo str_replace(["🛏️", "🛡️", "🌐", "🍳", "🚗", "🛎", "❌", "📜", "👥", "✨"], "", $item['title']); ?>
                </button>
            <?php endforeach; ?>
        </div>
        
        <div class="chatbot-input">
            <input 
                type="text" 
                id="chatbot-user-input" 
                placeholder="Escribe tu pregunta aquí..." 
                aria-label="Escribe tu pregunta"
            >
            <button id="chatbot-send">
                Enviar
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar Font Awesome si no está presente
    if (!document.querySelector('.fa-comments')) {
        const faScript = document.createElement('script');
        faScript.src = 'https://kit.fontawesome.com/a076d05399.js';
        document.head.appendChild(faScript);
    }

    // Función para normalizar texto (quitar acentos y convertir a minúsculas)
    function normalizeText(text) {
        return text.toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .replace(/[¿?¡!.,-]/g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    // Función para calcular distancia de Levenshtein (similitud entre strings)
    function levenshteinDistance(a, b) {
        if(a.length === 0) return b.length;
        if(b.length === 0) return a.length;
        
        const matrix = [];
        for(let i = 0; i <= b.length; i++) matrix[i] = [i];
        for(let j = 0; j <= a.length; j++) matrix[0][j] = j;
        
        for(let i = 1; i <= b.length; i++) {
            for(let j = 1; j <= a.length; j++) {
                const cost = a[j-1] === b[i-1] ? 0 : 1;
                matrix[i][j] = Math.min(
                    matrix[i-1][j] + 1,
                    matrix[i][j-1] + 1,
                    matrix[i-1][j-1] + cost
                );
            }
        }
        
        return matrix[b.length][a.length];
    }

    // Función para calcular similitud (0-1)
    function similarity(a, b) {
        // Para palabras muy cortas, solo coincidencia exacta
        if (a.length < 3 || b.length < 3) {
            return a === b ? 1 : 0;
        }
        
        const distance = levenshteinDistance(a, b);
        const maxLength = Math.max(a.length, b.length);
        return 1 - (distance / maxLength);
    }

    // Detectar modo oscuro del body
    function isDarkMode() {
        return document.body.classList.contains('dark-mode');
    }

    // Aplicar modo oscuro si es necesario
    function applyDarkMode() {
        if (isDarkMode()) {
            document.documentElement.style.setProperty('--chatbot-bg', '#1e1e1e');
            document.documentElement.style.setProperty('--chatbot-text', '#e0e0e0');
            // ... otras propiedades de modo oscuro
        }
    }

    // Verificar el modo al cargar
    applyDarkMode();

    // Observar cambios en el body para modo oscuro
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                applyDarkMode();
            }
        });
    });

    observer.observe(document.body, {
        attributes: true
    });

    const chatbot = {
        toggle: document.getElementById('chatbot-toggle'),
        container: document.getElementById('chatbot-container'),
        closeBtn: document.getElementById('chatbot-close'),
        input: document.getElementById('chatbot-user-input'),
        sendBtn: document.getElementById('chatbot-send'),
        messages: document.getElementById('chatbot-messages'),
        menuItems: document.querySelectorAll('.menu-item'),
        
        init() {
            this.setupEvents();
            this.setupMenuItems();
        },
        
        setupEvents() {
            this.toggle.addEventListener('click', () => this.toggleChat());
            this.closeBtn.addEventListener('click', () => this.hideChat());
            this.input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.sendMessage();
                }
            });
            this.sendBtn.addEventListener('click', () => this.sendMessage());
        },
        
        setupMenuItems() {
            this.menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remover clase active de todos los items
                    document.querySelectorAll('.menu-item').forEach(i => {
                        i.classList.remove('active');
                    });
                    // Agregar clase active al item clickeado
                    this.classList.add('active');
                });
            });
        },
        
        toggleChat() {
            this.container.classList.toggle('show');
            if (this.container.classList.contains('show')) {
                this.input.focus();
                // Desplazarse al final de los mensajes
                setTimeout(() => {
                    this.messages.scrollTop = this.messages.scrollHeight;
                }, 100);
            }
        },
        
        hideChat() {
            this.container.classList.remove('show');
        },
        
        sendMessage() {
            const message = this.input.value.trim();
            if (!message) {
                this.addMessage("Por favor escribe algo para que pueda ayudarte.", 'bot');
                return;
            }
            
            this.addMessage('Tú: ' + message, 'user');
            this.input.value = '';
            
            // Mostrar indicador de que el bot está pensando
            const thinkingMsg = this.addMessage("Estoy buscando información...", 'bot');
            
            setTimeout(() => {
                // Eliminar mensaje de "pensando"
                this.messages.removeChild(thinkingMsg);
                
                const response = this.getResponse(message);
                this.addMessage(response, 'bot');
            }, 800);
        },
        
        addMessage(text, sender) {
            // Si es el primer mensaje, limpiar el mensaje de bienvenida
            if (document.querySelector('.welcome-message') && sender === 'user') {
                this.messages.innerHTML = '';
            }
            
            const msgDiv = document.createElement('div');
            msgDiv.className = `${sender}-message`;
            msgDiv.innerHTML = text;
            this.messages.appendChild(msgDiv);
            this.messages.scrollTop = this.messages.scrollHeight;
        },
        
        getResponse(message) {
            const faq = <?php echo json_encode($faq); ?>;
            const normalizedMsg = normalizeText(message);
            
            // Primero buscar coincidencia exacta o muy similar
            let bestMatch = null;
            let highestScore = 0;
            const threshold = 0.7; // Umbral de similitud aceptable
            
            for (const [key, item] of Object.entries(faq)) {
                // Buscar en el título y las keywords
                const normalizedTitle = normalizeText(item.title);
                const titleScore = similarity(normalizedMsg, normalizedTitle);
                
                // Buscar en cada keyword
                let keywordScore = 0;
                for (const keyword of item.keywords) {
                    const normalizedKeyword = normalizeText(keyword);
                    const currentScore = similarity(normalizedMsg, normalizedKeyword);
                    if (currentScore > keywordScore) {
                        keywordScore = currentScore;
                    }
                }
                
                // Tomar el mejor score entre título y keywords
                const currentScore = Math.max(titleScore, keywordScore);
                
                if (currentScore > highestScore) {
                    highestScore = currentScore;
                    bestMatch = item;
                }
            }
            
            // Si encontramos una coincidencia buena
            if (bestMatch && highestScore >= threshold) {
                return `<strong>${bestMatch.title}</strong><br>${bestMatch.content}`;
            }
            
            // Si no, buscar términos similares para sugerencias
            const suggestions = [];
            for (const [key, item] of Object.entries(faq)) {
                const normalizedTitle = normalizeText(item.title);
                const currentScore = similarity(normalizedMsg, normalizedTitle);
                
                if (currentScore >= 0.5) { // Umbral más bajo para sugerencias
                    suggestions.push({
                        item: item,
                        score: currentScore
                    });
                }
            }
            
            // Ordenar sugerencias por score
            suggestions.sort((a, b) => b.score - a.score);
            
            if (suggestions.length > 0) {
                const suggestionsHtml = suggestions.slice(0, 3).map(suggestion => 
                    `• <span class="quick-question" onclick="sendQuickQuestion('${Object.keys(faq).find(k => faq[k] === suggestion.item)}')" 
                      style="cursor:pointer;color:var(--chatbot-primary)">${suggestion.item.title}</span>`
                ).join('<br>');
                
                return `¿Quizás te interesa saber sobre?<br><br>${suggestionsHtml}`;
            }
            
            // Si no encuentra nada, mostrar opciones principales
            const mainOptions = Object.values(faq).slice(0, 6).map(item => 
                `• <span class="quick-question" onclick="sendQuickQuestion('${Object.keys(faq).find(k => faq[k] === item)}')" 
                  style="cursor:pointer;color:var(--chatbot-primary)">${item.title}</span>`
            ).join('<br>');
            
            return `No estoy seguro de lo que preguntas. ¿En qué puedo ayudarte?<br><br>${mainOptions}`;
        }
    };
    
    // Función global para los botones rápidos
    window.sendQuickQuestion = function(topic) {
        const faq = <?php echo json_encode($faq); ?>;
        if (faq[topic]) {
            // Limpiar mensaje de bienvenida si es el primer mensaje
            if (document.querySelector('.welcome-message')) {
                document.getElementById('chatbot-messages').innerHTML = '';
            }
            
            // Resaltar el ítem del menú seleccionado
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Agregar el mensaje
            chatbot.addMessage(`<strong>${faq[topic].title}</strong><br>${faq[topic].content}`, 'bot');
            chatbot.messages.scrollTop = chatbot.messages.scrollHeight;
        }
    };
    
    chatbot.init();
});
</script>