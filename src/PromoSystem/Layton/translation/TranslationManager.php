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

use pocketmine\utils\Config;
use PromoSystem\Layton\PromoSystem;

class TranslationManager {

    private string $languageName = "eng";

    private array $translations = [];

    private QueryHelper $queryHelper;

    public function __construct(PromoSystem $plugin) {
        @mkdir($translationDirectory = $plugin->getDataFolder() . "translations\\");

        foreach ($plugin->getResources() as $resource) {
            if ($resource->isFile()) {
                $filename = $resource->getFilename();
                if (str_starts_with($filename, "translation_") and str_ends_with($filename, ".json")) {
                    $file = $translationDirectory . $filename;

                    if (!file_exists($file)) {
                        file_put_contents($file, file_get_contents($resource->getPathname()));
                    }

                    $translation = json_decode(file_get_contents($file), true);
                    $this->translations[$translation["name"]] = $translation;
                }
            }
        }

        $language = $plugin->getConfig()->get("language");
        if (array_key_exists($language, $this->translations)) {
            $this->languageName = $language;
        }

        $this->queryHelper = new QueryHelper($this->languageName, $this->translations);
    }

    public function getLanguageName(): string {
        return $this->languageName;
    }

    public function getTranslations(): array {
        return $this->translations;
    }

    public function getQueryHelper(): QueryHelper {
        return $this->queryHelper;
    }

}