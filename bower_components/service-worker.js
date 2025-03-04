const cacheName = 'mi-app-cache';
const filesToCache = [
  '/',
  '/gescon/',
  '/gescon/content/dist/gescon2.ico',
  '/gescon/manifest.json',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(cacheName).then((cache) => {
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});



