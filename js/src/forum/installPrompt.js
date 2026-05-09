import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import HeaderSecondary from 'flarum/forum/components/HeaderSecondary';
import Button from 'flarum/common/components/Button';

const DISMISSED_KEY = 'datlechin-pwa.installDismissed';

let deferredPrompt = null;
let installed = false;

/**
 * Catches the browser's `beforeinstallprompt` event so we can show our own
 * install button later. Without this hook the browser may either:
 *   - show its own install banner (which the user usually ignores), or
 *   - silently meet engagement criteria with no visible affordance.
 *
 * The captured event is used once the user clicks our header button.
 */
export default function installPrompt() {
  window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredPrompt = event;
    m.redraw();
  });

  window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    installed = true;
    m.redraw();
  });

  extend(HeaderSecondary.prototype, 'items', function (items) {
    if (installed) return;
    if (!deferredPrompt) return;
    if (sessionStorage.getItem(DISMISSED_KEY)) return;

    items.add(
      'datlechin-pwa.install',
      <Button
        className="Button Button--link Button--icon DatlechinPwa-installButton"
        icon="fas fa-download"
        title={app.translator.trans('datlechin-pwa.forum.install_tooltip')}
        onclick={async () => {
          if (!deferredPrompt) return;
          const prompt = deferredPrompt;
          deferredPrompt = null;
          m.redraw();

          const choice = await prompt.prompt().then(() => prompt.userChoice);
          if (choice && choice.outcome === 'dismissed') {
            // Don't keep nagging this session. Browser-side logic still
            // gates whether we'll be offered the prompt again later.
            sessionStorage.setItem(DISMISSED_KEY, '1');
          }
        }}
      >
        {app.translator.trans('datlechin-pwa.forum.install_button')}
      </Button>,
      80
    );
  });
}
