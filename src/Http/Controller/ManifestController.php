<?php

/*
 * This file is part of datlechin/flarum-pwa.
 *
 * Copyright (c) 2026 Ngo Quoc Dat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Datlechin\Pwa\Http\Controller;

use Flarum\Foundation\Config;
use Flarum\Settings\SettingsRepositoryInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Serves the Web App Manifest at /manifest.webmanifest.
 *
 * Browsers fetch this in response to the `<link rel="manifest">` we inject
 * into the forum's <head>. Falls back to forum-level defaults so the
 * extension is useful immediately after install with no configuration.
 */
class ManifestController implements RequestHandlerInterface
{
    public function __construct(
        protected SettingsRepositoryInterface $settings,
        protected Config $config,
    ) {
    }

    public function handle(Request $request): ResponseInterface
    {
        $forumTitle = (string) $this->settings->get('forum_title', 'Forum');
        $name = trim((string) $this->settings->get('datlechin-pwa.name', '')) ?: $forumTitle;
        $shortName = trim((string) $this->settings->get('datlechin-pwa.short_name', '')) ?: mb_substr($name, 0, 12);

        $themeColor = (string) $this->settings->get('datlechin-pwa.theme_color', '')
            ?: (string) $this->settings->get('theme_primary_color', '#3B82F6');
        $backgroundColor = (string) $this->settings->get('datlechin-pwa.background_color', '#ffffff');
        $display = (string) $this->settings->get('datlechin-pwa.display', 'standalone');

        $icons = $this->resolveIcons();

        $payload = array_filter([
            'name' => $name,
            'short_name' => $shortName,
            'description' => (string) $this->settings->get('forum_description', '') ?: null,
            'start_url' => $this->config->url()->getPath().'/',
            'scope' => $this->config->url()->getPath().'/',
            'display' => $display,
            'orientation' => 'portrait-primary',
            'theme_color' => $themeColor,
            'background_color' => $backgroundColor,
            'lang' => (string) $this->settings->get('default_locale', 'en'),
            'icons' => $icons,
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);

        return new JsonResponse($payload, 200, [
            'Content-Type' => 'application/manifest+json; charset=utf-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }

    /**
     * @return list<array<string, string>>
     */
    private function resolveIcons(): array
    {
        $icons = [];

        $icon192 = (string) $this->settings->get('datlechin-pwa.icon_192', '');
        $icon512 = (string) $this->settings->get('datlechin-pwa.icon_512', '');

        if ($icon192 !== '') {
            $icons[] = [
                'src' => $this->absoluteUrl($icon192),
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ];
        }
        if ($icon512 !== '') {
            $icons[] = [
                'src' => $this->absoluteUrl($icon512),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ];
        }

        // Fall back to the forum's own favicon. It probably isn't sized for
        // PWA install screens but it's better than no icon at all and many
        // browsers will scale gracefully.
        if ($icons === []) {
            $favicon = (string) $this->settings->get('favicon_path', '');
            if ($favicon !== '') {
                $icons[] = [
                    'src' => $this->absoluteUrl('assets/'.$favicon),
                    'sizes' => '192x192',
                    'type' => 'image/png',
                ];
            }
        }

        return $icons;
    }

    private function absoluteUrl(string $url): string
    {
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }
        $base = (string) $this->config->url();
        return rtrim($base, '/').'/'.ltrim($url, '/');
    }
}
