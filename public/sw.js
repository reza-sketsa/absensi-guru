const CACHE_NAME = "siskul-v1";
const STATIC_ASSETS = ["/css/app-custom.css"]; // hapus "/" dari sini

// Route yang TIDAK boleh di-cache
const EXCLUDE_FROM_CACHE = ["/login", "/logout", "/csrf-token", "/"];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS)),
  );
});

self.addEventListener("fetch", (event) => {
  if (event.request.method !== "GET") return;

  const url = new URL(event.request.url);

  // Jangan cache auth routes — selalu ambil dari network langsung
  if (EXCLUDE_FROM_CACHE.includes(url.pathname)) {
    event.respondWith(fetch(event.request));
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        const clone = response.clone();
        caches
          .open(CACHE_NAME)
          .then((cache) => cache.put(event.request, clone));
        return response;
      })
      .catch(() => caches.match(event.request)),
  );
});
