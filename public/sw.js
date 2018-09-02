"use strict";

const CACHE_NAME = `v1.0.1`;

const CACHE_FILES = [
    './',
    '/css/app.css',
    'css/font-awesome.all.css',
    '/js/app.js',
];

self.addEventListener('install', event => {
    console.log(`[ServiceWorker] Installed`);

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log(`[ServiceWorker] Caching CACHE_FILES`);
                return cache.addAll(CACHE_FILES);
            })
            .then(() => {
                console.log(`[ServiceWorker] All required resources have been cached, we\'re good!`);
                return self.skipWaiting();
            })
            .catch(error => {
                console.log(`[ServiceWorker] Installation failed: ${error}`);
            })
    );
});

self.addEventListener('activate', event => {
    console.log(`[ServiceWorker] Activated`);

    event.waitUntil(
        caches
            .keys()
            .then(keyList => {
                return Promise.all(
                    keyList.map(keyCache => {
                        if (keyCache !== CACHE_NAME) {
                            console.log(`[ServiceWorker] Removing Cached Files from ${keyCache}`);
                            return caches.delete(keyCache);
                        }
                    })
                );
            })
            .then(() => {
                return self.clients.claim();
            })
    );
});

self.addEventListener('fetch', event => {
    console.log(`[ServiceWorker] The service worker is serving the asset.`);

    event.respondWith(
        caches
            .match(event.request)
            .then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                console.log(`[ServiceWorker] fetch url - ${event.request.url}`);

                return fetch(event.request);
            })
            .catch(error => {
                console.log(error);
                return caches.match('./');
            })
    );
});

self.addEventListener('message', event => {
    console.log(event);
    console.log(`[ServiceWorker] have just got the message: ${event.data}`);
});
