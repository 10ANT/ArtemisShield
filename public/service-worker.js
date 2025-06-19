const CACHE_NAME = 'artemisshield-v1';
// This list should include the essential files your app needs to run offline.
// The root '/' is the main HTML. The rest are your core assets.
const urlsToCache = [
  '/',
  '/offline.html', // A fallback page for when network requests fail
  '/css/app.css',  // Adjust this path to your main CSS file
  '/js/app.js'     // Adjust this path to your main JS file
];

// 1. Installation: Open the cache and add the core assets.
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// 2. Fetch: Intercept network requests.
self.addEventListener('fetch', event => {
  event.respondWith(
    // Strategy: Cache-First, then Network
    caches.match(event.request)
      .then(response => {
        // If the request is in the cache, return it
        if (response) {
          return response;
        }

        // Otherwise, fetch it from the network
        return fetch(event.request).then(
          networkResponse => {
            if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
              return networkResponse;
            }

            // IMPORTANT: Clone the response. A response is a stream
       
            const responseToCache = networkResponse.clone();

            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });

            return networkResponse;
          }
        ).catch(() => {
          // If the network request fails (e.g., user is offline),
          // return the offline fallback page.
          return caches.match('/offline.html');
        });
      })
    );
});

// 3. Activation: Clean up old caches.
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});