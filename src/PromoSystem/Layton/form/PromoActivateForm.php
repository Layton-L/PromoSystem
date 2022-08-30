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

namespace PromoSystem\Layton\form;

use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;
use PromoSystem\Layton\response\Response;

class PromoActivateForm extends CustomForm {

    public function __construct(string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = null) use ($queryHelper) {
            if ($data === null) return;

            $dataManager = PromoSystem::getInstance()->getDataManager();
            $promo = trim($data["promo"]);

            if ($promo === "") {
                $player->sendForm(new PromoActivateForm("module.promo.form.input.empty"));
                return;
            }

            if (!$dataManager->isCreated($promo)) {
                $player->sendForm(new PromoActivateForm("module.promo.message.error.uncreated"));
                return;
            }

            if ($dataManager->isActivatedByUser($player, $promo)) {
                $player->sendForm(new PromoActivateForm("module.promo.message.error.activated"));
                return;
            }

            $uses = $dataManager->getUses($promo);
            $amount = $dataManager->getAmount($promo);

            if ($dataManager->isUsesLimited($promo)) {
                $maxUses = $dataManager->getMaxUses($promo);

                if ($uses >= $maxUses) {
                    $player->sendForm(new PromoActivateForm("module.promo.message.error.ended"));
                    return;
                }
            } else {
                $creationTime = $dataManager->getCreationTime($promo);
                $actionTime = $dataManager->getActionTime($promo);

                if (time() >= ($creationTime + $actionTime)) {
                    $player->sendForm(new PromoActivateForm("module.promo.message.error.ended"));
                    return;
                }
            }

            if ($dataManager->addToUser($player, $promo) instanceof Response) {
                $player->sendForm(new PromoActivateForm("module.promo.message.error.cancelled"));
                return;
            }

            $dataManager->setUses($promo, $uses + 1);
            BedrockEconomyAPI::getInstance()->addToPlayerBalance($player->getName(), $amount);

            $player->sendMessage(str_replace("%amount%", (string) $amount, $queryHelper->getTranslatedString("module.promo.message.success")));
        });
        $this->setTitle($queryHelper->getTranslatedString("module.promo.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.promo.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.promo.form.input.text"), $queryHelper->getTranslatedString("module.promo.form.input.placeholder"), "", "promo");
    }
    
}