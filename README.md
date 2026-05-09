# PWA

![License](https://img.shields.io/badge/license-MIT-blue.svg)

Make your Flarum forum installable as a Progressive Web App. Visitors get an "Install" button on supported browsers; once installed, the forum opens like a native app from the home screen, with a splash screen and offline-friendly cache.

## What it does

- Serves a Web App Manifest at `/manifest.webmanifest` with the forum's name, icons, theme color, and display mode.
- Serves a service worker at `/sw.js` that does stale-while-revalidate for same-origin GETs and stays out of the way of the API.
- Injects the manifest link, theme-color meta, and Apple-specific meta tags into the forum's HTML head.
- Catches the browser's `beforeinstallprompt` event so the install button lives in the forum header instead of behind a hidden browser menu.

## Installation

```sh
composer require datlechin/flarum-pwa
```

Enable from Admin → Extensions, then run:

```sh
php flarum cache:clear
```

## Configuration

Admin → Extensions → PWA.

| Setting | What it does |
|---|---|
| `name` | Full app name on the install dialog. Defaults to the forum title. |
| `short_name` | Label under the home screen icon. Keep it under 12 characters. |
| `theme_color` | OS chrome tint (status bar / address bar) when the app is open. |
| `background_color` | Splash screen color before the page renders. |
| `display` | `standalone`, `fullscreen`, `minimal-ui`, or `browser`. |
| `icon_192` / `icon_512` | URLs to PNG icons. 512x512 is required for the install dialog and splash screen. |
| `sw_enabled` | Toggle the service worker registration on the forum side. |
| `sw_version` | Bump this to force every browser to drop the old cache. |

## Notes

- Browsers only treat the page as installable over HTTPS (or `localhost` for development).
- The "Install" button only appears once the browser fires `beforeinstallprompt`. Some browsers gate this on engagement signals (a few seconds on the page, multiple visits) so it won't necessarily show on the first load.
- iOS doesn't fire `beforeinstallprompt`. Users still install via Safari → Share → Add to Home Screen; the manifest and apple-mobile-web-app meta tags ensure the result behaves like a real app.
- The service worker caches same-origin GETs only; the API and cross-origin assets always go through the network.

## Links

- [GitHub](https://github.com/datlechin/flarum-pwa)
