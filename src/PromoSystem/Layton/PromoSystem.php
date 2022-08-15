<?php

declare(strict_types = 1);

namespace PromoSystem\Layton;

use pocketmine\plugin\PluginBase;

class PromoSystem extends PluginBase {

    private static PromoSystem $instance;

    //private TranslationManager $translationManager;

    //private DataManager $dataManager;

    public static function getInstance(): PromoSystem {
        return self::$instance;
    }

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {

    }

    //public function getTranslationManager(): TranslationManager {
        //return $this->translationManager;
    //}

    //public function getDataManager(): DataManager {
        //return $this->dataManager;
    //}

}