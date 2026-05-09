/**
 * Registers the service worker emitted by ServiceWorkerController.
 *
 * Held until window.load so the SW install doesn't compete with the
 * initial page render. Failures are swallowed: a forum without an SW
 * still works fine, and there's no useful action a user can take if
 * registration fails.
 */
export default function registerServiceWorker() {
  if (!('serviceWorker' in navigator)) return;
  if (location.protocol !== 'https:' && location.hostname !== 'localhost') return;

  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(() => {});
  });
}
