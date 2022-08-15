<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\translation;

class QueryHelper {

    public function __construct(private string $languageName, private array $translations) {

    }

    public function getTranslatedString(string $query): string {
        $keys = explode(".", $query);
        $translation = $this->translations[$this->languageName];

        if (count($keys) == 1 && $keys[0] == $query) {
            $data = $translation[$query];
        } else {
            $data = [];
            foreach ($keys as $key) {
                if (empty($data)) {
                    $data = $translation[$key];
                } else {
                    $data = $data[$key];
                }
            }
        }

        return !is_string($data) ? "" : $data;
    }

}