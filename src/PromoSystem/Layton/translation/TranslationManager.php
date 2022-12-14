<?php

/**
 * PromoSystem, a promo codes system plugin for PocketMine-MP
 * Copyright (c) 2022 Layton-L  < https://github.com/Layton-L >
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * PromoSystem is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 */

declare(strict_types = 1);

namespace PromoSystem\Layton\translation;

use PromoSystem\Layton\PromoSystem;
use SplFileInfo;

class TranslationManager {

    private array $translations = [];

    private string $defaultLanguage = "eng";

    private string $currentLanguage;

    private QueryHelper $queryHelper;

    public function __construct(PromoSystem $plugin) {
        @mkdir($translationsDirectory = $plugin->getDataFolder() . "translations\\");

        $this->loadTranslations($plugin, $translationsDirectory);
        if ($plugin->getConfig()->get("custom_translations_enabled")) {
            $this->loadCustomTranslations($translationsDirectory);
        }

        $language = $plugin->getConfig()->get("language");
        if (array_key_exists($language, $this->translations)) {
            $this->currentLanguage = $language;
        } else {
            $this->currentLanguage = $this->defaultLanguage;
        }

        $this->queryHelper = new QueryHelper($this->currentLanguage, $this->translations);
    }

    private function loadTranslations(PromoSystem $plugin, string $translationsDirectory): void {
        foreach ($plugin->getResources() as $resource) {
            if ($resource->isFile()) {
                $filename = $resource->getFilename();
                if (str_starts_with($filename, "translation_") and str_ends_with($filename, ".json")) {
                    $file = $translationsDirectory . $filename;

                    if (!file_exists($file)) {
                        file_put_contents($file, file_get_contents($resource->getPathname()));
                    }

                    $this->translations[explode("_", explode(".", $filename)[0])[1]] = json_decode(file_get_contents($file), true);
                }
            }
        }
    }

    private function loadCustomTranslations(string $translationsDirectory): void {
        foreach (scandir($translationsDirectory) as $resource) {
            $resource = new SplFileInfo($translationsDirectory . $resource);

            if ($resource->isFile()) {
                $filename = $resource->getFilename();

                if (str_starts_with($filename, "translation_") and str_ends_with($filename, ".json")) {
                    $this->translations[explode("_", explode(".", $filename)[0])[1]] = json_decode(file_get_contents($resource->getPathname()), true);
                }
            }
        }
    }

    public function getTranslations(): array {
        return $this->translations;
    }

    public function getDefaultLanguage(): string {
        return $this->defaultLanguage;
    }

    public function getCurrentLanguage(): string {
        return $this->currentLanguage;
    }

    public function getQueryHelper(): QueryHelper {
        return $this->queryHelper;
    }

}