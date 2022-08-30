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

declare(strict_types=1);

namespace PromoSystem\Layton\form\admin;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;
use PromoSystem\Layton\response\Response;

class PromoCreateUsesLimitedForm extends CustomForm {

    public function __construct(string $promo, int $amount, string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = null) use ($promo, $amount, $queryHelper) {
            if ($data === null) return;

            $dataManager = PromoSystem::getInstance()->getDataManager();
            $max_uses = trim($data["max_uses"]);

            if ($max_uses === "") {
                $player->sendForm(new PromoCreateUsesLimitedForm($promo, $amount, "module.admin.create.uses_limited.form.input.empty"));
                return;
            }

            if (!(ctype_digit($max_uses) && (int) $max_uses > 0)) {
                $player->sendForm(new PromoCreateUsesLimitedForm($promo, $amount, "module.admin.create.message.error.uses_limited"));
                return;
            }

            if ($dataManager->createUsesLimited($promo, (int) $max_uses, $amount) instanceof Response) {
                $player->sendForm(new PromoCreateUsesLimitedForm($promo, $amount, "module.admin.create.message.error.cancelled"));
                return;
            }

            $player->sendMessage($queryHelper->getTranslatedString("module.admin.create.message.successful"));
        });
        $this->setTitle($queryHelper->getTranslatedString("module.admin.create.uses_limited.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.admin.create.uses_limited.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.admin.create.uses_limited.form.input.text"), $queryHelper->getTranslatedString("module.admin.create.uses_limited.form.input.placeholder"), "", "max_uses");
    }

}