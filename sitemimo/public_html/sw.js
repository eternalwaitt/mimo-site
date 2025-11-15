/**
 * Service Worker para Site Mimo
 * Cache offline e melhor performance
 * 
 * Versão: 2.3.9
 */
 
const CACHE_NAME = 'mimo-site-v2.3.9';
const RUNTIME_CACHE = 'mimo-runtime-v2';

// Assets para cache estático (imagens, CSS, JS críticos)
const STATIC_ASSETS = [
    '/',
    '/index.php',
    '/product.css',
    '/main.js',
    '/img/bgheader.jpg',
    '/img/bgheader.webp',
    '/img/bgheader.avif',
    '/img/logobranco.png',
    '/img/logobranco.webp',
    '/img/logobranco.avif',
    '/Akrobat-Regular.woff'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
    console.log('[SW] Installing service worker...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Caching static assets');
                // Cache apenas assets críticos na instalação
                return cache.addAll(STATIC_ASSETS.filter(url => {
                    // Filtrar apenas URLs válidas
                    try {
                        new URL(url, self.location.origin);
                        return true;
                    } catch {
                        return false;
                    }
                })).catch((err) => {
                    console.warn('[SW] Some assets failed to cache:', err);
                    // Continuar mesmo se alguns assets falharem
                    return Promise.resolve();
                });
            })
    );
    
    // Forçar ativação imediata
    self.skipWaiting();
});

// Ativação do Service Worker
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating service worker...');
    
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    // Limpar caches antigos
                    if (cacheName !== CACHE_NAME && cacheName !== RUNTIME_CACHE) {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    
    // Assumir controle imediato de todas as páginas
    return self.clients.claim();
});

// Interceptar requisições
self.addEventListener('fetch', (event) => {
    // Ignorar requisições não-GET
    if (event.request.method !== 'GET') {
        return;
    }
    
    // Ignorar requisições de extensões do Chrome
    if (event.request.url.startsWith('chrome-extension://')) {
        return;
    }
    
    const url = new URL(event.request.url);
    
    // Estratégia: Cache First para assets estáticos, Network First para páginas
    if (isStaticAsset(url.pathname)) {
        // Assets estáticos: Cache First
        event.respondWith(cacheFirst(event.request));
    } else if (isPageRequest(url.pathname)) {
        // Páginas: Network First com fallback para cache
        event.respondWith(networkFirst(event.request));
    } else {
        // Outros recursos: Network First
        event.respondWith(networkFirst(event.request));
    }
});

/**
 * Verifica se é um asset estático
 */
function isStaticAsset(pathname) {
    return /\.(jpg|jpeg|png|webp|avif|gif|svg|woff|woff2|ttf|eot|css|js)$/i.test(pathname) ||
           pathname.startsWith('/img/') ||
           pathname.startsWith('/minified/') ||
           pathname.startsWith('/bootstrap/');
}

/**
 * Verifica se é uma requisição de página
 */
function isPageRequest(pathname) {
    return pathname.endsWith('/') ||
           pathname.endsWith('.php') ||
           pathname.endsWith('.html') ||
           !pathname.includes('.');
}

/**
 * Estratégia: Cache First
 * Busca no cache primeiro, depois na rede
 */
async function cacheFirst(request) {
    const cache = await caches.open(CACHE_NAME);
    const cached = await cache.match(request);
    
    if (cached) {
        return cached;
    }
    
    try {
        const response = await fetch(request);
        
        // Cache apenas respostas válidas
        if (response.status === 200) {
            cache.put(request, response.clone());
        }
        
        return response;
    } catch (error) {
        console.error('[SW] Fetch failed:', error);
        
        // Fallback para página offline se disponível
        if (isPageRequest(new URL(request.url).pathname)) {
            const offlinePage = await cache.match('/offline.html');
            if (offlinePage) {
                return offlinePage;
            }
        }
        
        throw error;
    }
}

/**
 * Estratégia: Network First
 * Busca na rede primeiro, depois no cache
 */
async function networkFirst(request) {
    const cache = await caches.open(RUNTIME_CACHE);
    
    try {
        const response = await fetch(request);
        
        // Cache apenas respostas válidas
        if (response.status === 200) {
            cache.put(request, response.clone());
        }
        
        return response;
    } catch (error) {
        console.log('[SW] Network failed, trying cache:', request.url);
        
        const cached = await cache.match(request);
        if (cached) {
            return cached;
        }
        
        // Fallback para página offline se disponível
        if (isPageRequest(new URL(request.url).pathname)) {
            const offlinePage = await cache.match('/offline.html');
            if (offlinePage) {
                return offlinePage;
            }
        }
        
        throw error;
    }
}

