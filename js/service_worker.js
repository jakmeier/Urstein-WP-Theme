/// Cache for pictures and other content which generally doesn't update. (If it does, the URI should change, too.)
const STATIC_CONTENT_CACHE= 'static-cache';
/// General-purpose cache applied to all requests not handled by other requests.
const RUNTIME_CACHE = 'runtime-cache';

// const PREFIX = '/wordpress'; // Only for local testing necessary
const PREFIX = '';
const OFFLINE_URL = PREFIX + '/offline/'; // This needs to be created in WP admin panel
const OFFLINE_IMAGE_URL = PREFIX + '/offline.jpg'; // This needs to be stored manually, like favicons etc

const STATIC_CONTENT_SUFFIXES = [
    '.png',
    '.jpg',
    '.jpeg',
];

self.addEventListener('install', event => {
    // Most pages are dynamically managed (PHP/Wordpress) so it makes little sense to cache anything by static URL.
    // But offline page will never be loaded unless we force to load it at some point, hence it is loaded here on install.
    event.waitUntil(
        caches.open(RUNTIME_CACHE).then(function(cache) {
            return cache.addAll([OFFLINE_URL, OFFLINE_IMAGE_URL]);
        })
    );
});

// When a (new) version of the SW is activated, some of the old caches might need to be cleaned.
self.addEventListener('activate', event => {
    // Nothing to replace on activation for now
});

self.addEventListener('fetch', event => {
    // Only use caches for same origin GETs
    if (event.request.url.startsWith(self.location.origin) && event.method === "GET") {
        const isStatic = STATIC_CONTENT_SUFFIXES.find(suffix => event.request.url.endsWith(suffix));
        if (isStatic) {
            // Read from cache and only send network request if no match found. Cache on success.
            event.respondWith( caches
                .open(STATIC_CONTENT_CACHE)
                .then(cache => cache
                    .match(event.request)
                    .then( cachedResponse => {
                        return cachedResponse || fetch(event.request).then( response =>
                            {
                                if (response) {
                                    return cache.put(event.request, response.clone())
                                        .then(() => response);
                                } else {
                                    if (!navigator.onLine) { return caches.match(OFFLINE_IMAGE_URL); }
                                    return response;
                                }
                            } 
                        )
                    })  
                )
            );
        } else {
            // Always try to fetch from network first but cache all responses on success, fall back to offline cache if no network available
            event.respondWith(
                fetch(event.request)
                .then(
                    response => {
                        if (response) {
                            return caches
                                .open(RUNTIME_CACHE)
                                .then( cache => cache
                                    .put(event.request, response.clone())
                                    .then(() => response)
                                );
                        } else {
                            return caches
                                .open(RUNTIME_CACHE)
                                .then( cache => cache.match(event.request))
                                .then( cachedResponse => {
                                    if (cachedResponse) { return cachedResponse; }
                                    if (!navigator.onLine) { return caches.match(OFFLINE_URL); }
                                    return response;
                                })
                        }
                    }
                )
            );
        }
    }
});