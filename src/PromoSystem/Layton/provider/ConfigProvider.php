<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use pocketmine\utils\Config;
use PromoSystem\Layton\PromoSystem;

class ConfigProvider implements Provider {

    private Config $config;

    public function __construct(PromoSystem $plugin, int $type) {
        $format = array_flip(Config::$formats)[$type];
        $this->config = new Config($plugin->getDataFolder() . "database." . $format, $type);
    }

}