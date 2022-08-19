<?php

declare(strict_types = 1);

namespace PromoSystem\Layton;

use pocketmine\plugin\PluginBase;
use PromoSystem\Layton\command\PromoAdminCommand;
use PromoSystem\Layton\command\PromoCommand;
use PromoSystem\Layton\form\PromoActivateForm;
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
            default => new SQLite3Provider($this),
        };

        $this->translationManager = new TranslationManager($this);
        $this->dataManager = new DataManager($provider);
        $this->registerCommands();
    }

    public function onEnable(): void {

    }

    private function registerCommands(): void {
        $queryHelper = $this->getTranslationManager()->getQueryHelper();
        $map = $this->getServer()->getCommandMap();

        $map->registerAll("PromoSystem", [
            new PromoCommand("promo", $queryHelper->getTranslatedString("command.promo.description")),
            new PromoAdminCommand("promo-admin", $queryHelper->getTranslatedString("command.promo-admin.description"))
        ]);
    }

    public function getTranslationManager(): TranslationManager {
        return $this->translationManager;
    }

    public function getDataManager(): DataManager {
        return $this->dataManager;
    }

}