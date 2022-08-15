<?php

declare(strict_types = 1);

namespace PromoSystem\Layton;

use pocketmine\plugin\PluginBase;
use PromoSystem\Layton\provider\SQLite3Provider;
use PromoSystem\Layton\translation\TranslationManager;

class PromoSystem extends PluginBase {

    private static PromoSystem $instance;

    private TranslationManager $translationManager;

    private DataManager $dataManager;

    public static function getInstance(): PromoSystem {
        return self::$instance;
    }

    public function onLoad(): void {
        self::$instance = $this;
        $this->saveDefaultConfig();

        $provider = match ($this->getConfig()->get("provider")) {
            //"json" => new ConfigProvider($this, Config::JSON),
            //"yaml" => new ConfigProvider($this, Config::YAML),
            default => new SQLite3Provider($this),
        };

        $this->translationManager = new TranslationManager($this);
        $this->dataManager = new DataManager($provider);
        //$this->registerCommands();
    }

    public function onEnable(): void {

    }

    public function getTranslationManager(): TranslationManager {
        return $this->translationManager;
    }

    public function getDataManager(): DataManager {
        return $this->dataManager;
    }

}