self.addEventListener("install", e => {
  e.waitUntil(caches.open("csas-v1").then(cache => {
    return cache.addAll(["index.html", "subscribe.html", "alerts.html", "stats.html", "style.css"]);
  }));
});
self.addEventListener("fetch", e => {
  e.respondWith(caches.match(e.request).then(r => r || fetch(e.request)));
});
