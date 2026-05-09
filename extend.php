<?php

/*
 * This file is part of datlechin/flarum-pwa.
 *
 * Copyright (c) 2026 Ngo Quoc Dat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Datlechin\Pwa;

use Datlechin\Pwa\Frontend\InjectPwaHeadTags;
use Datlechin\Pwa\Http\Controller\ManifestController;
use Datlechin\Pwa\Http\Controller\ServiceWorkerController;
use Flarum\Extend;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less')
        ->content(InjectPwaHeadTags::class),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Routes('forum'))
        ->get('/manifest.webmanifest', 'datlechin-pwa.manifest', ManifestController::class)
        ->get('/sw.js', 'datlechin-pwa.sw', ServiceWorkerController::class),

    (new Extend\Settings())
        ->default('datlechin-pwa.name', '')
        ->default('datlechin-pwa.short_name', '')
        ->default('datlechin-pwa.theme_color', '')
        ->default('datlechin-pwa.background_color', '#ffffff')
        ->default('datlechin-pwa.display', 'standalone')
        ->default('datlechin-pwa.icon_192', '')
        ->default('datlechin-pwa.icon_512', '')
        ->default('datlechin-pwa.sw_enabled', true)
        ->default('datlechin-pwa.sw_version', '1')
        ->serializeToForum('datlechinPwaSwEnabled', 'datlechin-pwa.sw_enabled', 'boolval'),
];
