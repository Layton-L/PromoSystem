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

class QueryHelper {

    public function __construct(private string $languageName, private array $translations) {

    }

    public function getTranslatedString(string $query): string {
        $keys = explode(".", $query);
        $translation = $this->translations[$this->languageName];

        if (count($keys) == 1 && $keys[0] == $query) {
            $data = $translation[$query] ?? "";
        } else {
            $data = [];
            foreach ($keys as $key) {
                if (empty($data)) {
                    if (empty($translation[$key])) {
                        return "";
                    }

                    $data = $translation[$key];
                } else {
                    if (empty($data[$key])) {
                        return "";
                    }

                    $data = $data[$key];
                }
            }
        }

        return !is_string($data) ? "" : $data;
    }

}