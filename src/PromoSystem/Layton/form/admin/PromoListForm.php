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

namespace PromoSystem\Layton\form\admin;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use PromoSystem\Layton\data\DataHelper;
use PromoSystem\Layton\PromoSystem;

class PromoListForm extends SimpleForm {

    public function __construct(int $index = 0) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();
        $dataManager = PromoSystem::getInstance()->getDataManager();

        $pages = array_chunk($dataManager->getAllPromos(), 5);
        parent::__construct(function (Player $player, int $data = null) use ($queryHelper, $dataManager, $pages, $index) {
            if ($data === null) return;

            if (count($pages) == 0) {
                $player->sendForm(new PromoListForm());
                return;
            }

            $lastIndex = array_key_last($pages[$index]);
            if ($data === $lastIndex + 1) {
                if (isset($pages[$index - 1])) {
                    $index--;
                } else {
                    $index = array_key_last($pages);
                }

                $player->sendForm(new PromoListForm($index));
                return;
            }

            if ($data == $lastIndex + 2) {
                if (isset($pages[$index + 1])) {
                    $index++;
                } else {
                    $index = array_key_first($pages);
                }

                $player->sendForm(new PromoListForm($index));
                return;
            }

            $promo = $pages[$index][$data];
            if ($dataManager->isCreated($promo)) {
                $player->sendMessage(DataHelper::getFormattedPromoString($promo));
            } else {
                $player->sendMessage($queryHelper->getCurrentTranslation("module.admin.info.message.error.uncreated"));
            }
        });
        $this->setTitle(str_replace("%page%", (string) ($index + 1), $queryHelper->getCurrentTranslation("module.admin.list.form.title")));

        if (isset($pages[$index])) {
            foreach ($pages[$index] as $promo) {
                $this->addButton($promo);
            }
        }

        $this->addButton($queryHelper->getCurrentTranslation("module.admin.list.form.button.back"));
        $this->addButton($queryHelper->getCurrentTranslation("module.admin.list.form.button.forward"));
    }

}