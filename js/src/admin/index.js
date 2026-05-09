import app from 'flarum/admin/app';

app.initializers.add('datlechin/flarum-pwa', () => {
  const ext = app.registry.for('datlechin-pwa');

  ext
    .registerSetting({
      setting: 'datlechin-pwa.name',
      label: app.translator.trans('datlechin-pwa.admin.settings.name_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.name_help'),
      type: 'text',
      placeholder: app.translator.trans('datlechin-pwa.admin.settings.name_placeholder'),
    })
    .registerSetting({
      setting: 'datlechin-pwa.short_name',
      label: app.translator.trans('datlechin-pwa.admin.settings.short_name_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.short_name_help'),
      type: 'text',
      placeholder: app.translator.trans('datlechin-pwa.admin.settings.short_name_placeholder'),
    })
    .registerSetting({
      setting: 'datlechin-pwa.theme_color',
      label: app.translator.trans('datlechin-pwa.admin.settings.theme_color_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.theme_color_help'),
      type: 'color',
    })
    .registerSetting({
      setting: 'datlechin-pwa.background_color',
      label: app.translator.trans('datlechin-pwa.admin.settings.background_color_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.background_color_help'),
      type: 'color',
    })
    .registerSetting({
      setting: 'datlechin-pwa.display',
      label: app.translator.trans('datlechin-pwa.admin.settings.display_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.display_help'),
      type: 'select',
      default: 'standalone',
      options: {
        standalone: app.translator.trans('datlechin-pwa.admin.settings.display_standalone'),
        fullscreen: app.translator.trans('datlechin-pwa.admin.settings.display_fullscreen'),
        'minimal-ui': app.translator.trans('datlechin-pwa.admin.settings.display_minimal'),
        browser: app.translator.trans('datlechin-pwa.admin.settings.display_browser'),
      },
    })
    .registerSetting({
      setting: 'datlechin-pwa.icon_192',
      label: app.translator.trans('datlechin-pwa.admin.settings.icon_192_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.icon_192_help'),
      type: 'text',
      placeholder: '/assets/pwa-192.png',
    })
    .registerSetting({
      setting: 'datlechin-pwa.icon_512',
      label: app.translator.trans('datlechin-pwa.admin.settings.icon_512_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.icon_512_help'),
      type: 'text',
      placeholder: '/assets/pwa-512.png',
    })
    .registerSetting({
      setting: 'datlechin-pwa.sw_enabled',
      label: app.translator.trans('datlechin-pwa.admin.settings.sw_enabled_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.sw_enabled_help'),
      type: 'boolean',
    })
    .registerSetting({
      setting: 'datlechin-pwa.sw_version',
      label: app.translator.trans('datlechin-pwa.admin.settings.sw_version_label'),
      help: app.translator.trans('datlechin-pwa.admin.settings.sw_version_help'),
      type: 'text',
    });
});
