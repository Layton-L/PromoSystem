<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\form\admin;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;
use PromoSystem\Layton\response\Response;

class PromoDeleteForm extends CustomForm {

    public function __construct(string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = null) use ($queryHelper) {
            if ($data === null) return;

            $dataManager = PromoSystem::getInstance()->getDataManager();
            $promo = trim($data["promo"]);

            if ($promo === "") {
                $player->sendForm(new PromoDeleteForm("module.admin.delete.form.input.empty"));
                return;
            }

            if (!$dataManager->isCreated($promo)) {
                $player->sendForm(new PromoDeleteForm("module.admin.delete.message.error.uncreated"));
                return;
            }

            if ($dataManager->delete($promo) instanceof Response) {
                $player->sendForm(new PromoDeleteForm("module.admin.delete.message.error.cancelled"));
                return;
            }

            $player->sendMessage($queryHelper->getTranslatedString("module.admin.delete.message.successful"));
        });
        $this->setTitle($queryHelper->getTranslatedString("module.admin.delete.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.admin.delete.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.admin.delete.form.input.text"), $queryHelper->getTranslatedString("module.admin.delete.form.input.placeholder"), "", "promo");
    }

}