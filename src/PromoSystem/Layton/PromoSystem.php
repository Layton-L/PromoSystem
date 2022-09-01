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

namespace PromoSystem\Layton;

use pocketmine\plugin\PluginBase;
use PromoSystem\Layton\command\PromoAdminCommand;
use PromoSystem\Layton\command\PromoCommand;
use PromoSystem\Layton\command\PromoLanguagesCommand;
use PromoSystem\Layton\data\DataManager;
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

        $this->getLogger()->info($this->translationManager->getQueryHelper()->getTranslatedString("status.loaded"));
    }

    public function onEnable(): void {
        $this->getLogger()->info($this->translationManager->getQueryHelper()->getTranslatedString("status.enabled"));
    }

    private function registerCommands(): void {
        $queryHelper = $this->getTranslationManager()->getQueryHelper();
        $map = $this->getServer()->getCommandMap();

        $map->registerAll("PromoSystem", [
            new PromoCommand("promo", $queryHelper->getTranslatedString("command.promo.description")),
            new PromoAdminCommand("promo-admin", $queryHelper->getTranslatedString("command.promo-admin.description")),
            new PromoLanguagesCommand("promo-languages", $queryHelper->getTranslatedString("command.promo-languages.description"))
        ]);
    }

    public function getTranslationManager(): TranslationManager {
        return $this->translationManager;
    }

    public function getDataManager(): DataManager {
        return $this->dataManager;
    }

}