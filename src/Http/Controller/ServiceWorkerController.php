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

use Flarum\Settings\SettingsRepositoryInterface;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Serves the service worker JS at /sw.js.
 *
 * `Service-Worker-Allowed: /` lets the SW take a scope wider than its own
 * URL. We don't strictly need it because /sw.js is already at the root,
 * but it's harmless and protects against subpath installs.
 *
 * The cache version is interpolated server-side from the
 * `datlechin-pwa.sw_version` setting. Bumping that setting (or hitting the
 * "Bust cache" button in admin) drops every old cache on the next page
 * load.
 */
class ServiceWorkerController implements RequestHandlerInterface
{
    public function __construct(
        protected SettingsRepositoryInterface $settings,
    ) {
    }

    public function handle(Request $request): ResponseInterface
    {
        $version = (string) $this->settings->get('datlechin-pwa.sw_version', '1');

        $template = (string) file_get_contents(__DIR__.'/../../../resources/sw.template.js');
        $body = strtr($template, ['{{VERSION}}' => $version]);

        return new TextResponse($body, 200, [
            'Content-Type' => 'application/javascript; charset=utf-8',
            'Service-Worker-Allowed' => '/',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
