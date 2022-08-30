<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\form\admin;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;

class PromoInfoForm extends CustomForm {

    public function __construct(string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = []) use ($queryHelper) {
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

            $message = str_replace("%promo%", $promo, $queryHelper->getTranslatedString("module.admin.info.message.successful"));
            $message = str_replace("%amount%", (string) $dataManager->getAmount($promo), $message);
            $message = str_replace("%creation_time%", date("F j, Y, g:i a", $dataManager->getCreationTime($promo)), $message);
            $message = str_replace("%uses%", (string) $dataManager->getUses($promo), $message);
            $message = str_replace("%max_uses%", (string) $dataManager->getMaxUses($promo), $message);
            $message = str_replace("%action_time%", (string) $dataManager->getActionTime($promo), $message);

            if ($dataManager->isUsesLimited($promo)) {
                $type = $queryHelper->getTranslatedString("promo.uses_limited");
            } else {
                $type = $queryHelper->getTranslatedString("promo.temporary");
            }

            $player->sendMessage(str_replace("%promo_type%", $type, $message));
        });
        $this->setTitle($queryHelper->getTranslatedString("module.admin.info.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.admin.info.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.admin.info.form.input.text"), $queryHelper->getTranslatedString("module.admin.info.form.input.placeholder"), "", "promo");
    }

}