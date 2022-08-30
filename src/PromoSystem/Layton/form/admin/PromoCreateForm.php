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
use PromoSystem\Layton\PromoSystem;

class PromoCreateForm extends CustomForm {

    public function __construct(string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = null) use ($queryHelper) {
            if ($data === null) return;

            $dataManager = PromoSystem::getInstance()->getDataManager();
            $promoSettings = PromoSystem::getInstance()->getConfig()->get("promo");

            $promo = trim($data["promo"]);
            $amount = trim($data["amount"]);
            $type = $data["type"];

            if ($promo === "") {
                $player->sendForm(new PromoCreateForm("module.admin.create.form.input.promo.empty"));
                return;
            }

            if ($dataManager->isCreated($promo)) {
                $player->sendForm(new PromoCreateForm("module.admin.create.message.error.promo.created"));
                return;
            }

            if (!preg_match("/^[a-z0-9]+$/i", $promo)) {
                $player->sendForm(new PromoCreateForm("module.admin.create.message.error.promo.format"));
                return;
            }

            if (strlen($promo) < $promoSettings["minimum_length"] || strlen($promo) > $promoSettings["maximum_length"]) {
                $player->sendForm(new PromoCreateForm("module.admin.create.message.error.promo.length"));
                return;
            }

            if ($amount === "") {
                $player->sendForm(new PromoCreateForm("module.admin.create.form.input.amount.empty"));
                return;
            }

            if (!(ctype_digit($amount) && (int) $amount > 0)) {
                $player->sendForm(new PromoCreateForm("module.admin.create.message.error.amount"));
                return;
            }

            $amount = (int) $amount;
            if ($type) {
                $player->sendForm(new PromoCreateTemporaryForm($promo, $amount));
            } else {
                $player->sendForm(new PromoCreateUsesLimitedForm($promo, $amount));
            }
        });
        $this->setTitle($queryHelper->getTranslatedString("module.admin.create.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.admin.create.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.admin.create.form.input.promo.text"), $queryHelper->getTranslatedString("module.admin.create.form.input.promo.placeholder"), "", "promo");
        $this->addInput($queryHelper->getTranslatedString("module.admin.create.form.input.amount.text"), $queryHelper->getTranslatedString("module.admin.create.form.input.amount.placeholder"), "", "amount");
        $this->addToggle($queryHelper->getTranslatedString("module.admin.create.form.toggle.text"), null, "type");
    }

}