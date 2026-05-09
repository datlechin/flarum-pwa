<?php

/*
 * This file is part of datlechin/flarum-pwa.
 *
 * Copyright (c) 2026 Ngo Quoc Dat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Datlechin\Pwa\Frontend;

use Flarum\Frontend\Document;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Adds the manifest link and the theme-color meta tag to the forum's
 * document head. Without these the browser doesn't know the page is
 * installable and the install banner / address bar tint won't kick in.
 */
class InjectPwaHeadTags
{
    public function __construct(
        protected SettingsRepositoryInterface $settings,
    ) {
    }

    public function __invoke(Document $document, ServerRequestInterface $request): Document
    {
        $themeColor = (string) $this->settings->get('datlechin-pwa.theme_color', '')
            ?: (string) $this->settings->get('theme_primary_color', '#3B82F6');

        $document->head['datlechin-pwa.manifest'] = '<link rel="manifest" href="/manifest.webmanifest">';
        $document->head['datlechin-pwa.theme'] = '<meta name="theme-color" content="'.htmlspecialchars($themeColor, ENT_QUOTES, 'UTF-8').'">';
        $document->head['datlechin-pwa.apple-mobile'] = '<meta name="apple-mobile-web-app-capable" content="yes">';
        $document->head['datlechin-pwa.apple-status'] = '<meta name="apple-mobile-web-app-status-bar-style" content="default">';

        return $document;
    }
}
