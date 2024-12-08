const CACHE_NAME = 'my-site-cache-v1';
const urlsToCache = [
    '/',
    '/index.html',
    '/css/styleDescricao.css',
    '/img/icon-192x192.png',
    '/img/icon-512x512.png',
];

// Instala o service worker e faz cache dos recursos
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Cache inicializado');
                return cache.addAll(urlsToCache);
            })
    );
});

// Recupera recursos do cache
self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                return response || fetch(event.request);
            })
    );
});