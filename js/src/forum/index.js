import app from 'flarum/forum/app';
import registerServiceWorker from './registerServiceWorker';
import installPrompt from './installPrompt';

app.initializers.add('datlechin/flarum-pwa', () => {
  if (app.forum.attribute('datlechinPwaSwEnabled')) {
    registerServiceWorker();
  }
  installPrompt();
});
