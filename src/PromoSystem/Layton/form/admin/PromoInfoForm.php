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

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\data\DataHelper;
use PromoSystem\Layton\PromoSystem;

class PromoInfoForm extends CustomForm {

    public function __construct(string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = null) use ($queryHelper) {
            if ($data === null) return;

            $dataManager = PromoSystem::getInstance()->getDataManager();
            $promo = trim($data["promo"]);

            if ($promo === "") {
                $player->sendForm(new PromoInfoForm("module.admin.info.form.input.empty"));
                return;
            }

            if (!$dataManager->isCreated($promo)) {
                $player->sendForm(new PromoInfoForm("module.admin.info.message.error.uncreated"));
                return;
            }

            $player->sendMessage(DataHelper::getFormattedPromoString($promo));
        });
        $this->setTitle($queryHelper->getCurrentTranslation("module.admin.info.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getCurrentTranslation("module.admin.info.form.label"));
        } else {
            $this->addLabel($queryHelper->getCurrentTranslation($error));
        }

        $this->addInput($queryHelper->getCurrentTranslation("module.admin.info.form.input.text"), $queryHelper->getCurrentTranslation("module.admin.info.form.input.placeholder"), "", "promo");
    }

}