<?php

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

            $promo = $data["promo"];
            $type = $data["type"];

            if ($promo === "") {
                $player->sendForm(new PromoCreateForm("module.admin.create.form.input.empty"));
                return;
            }

            if ($dataManager->isCreated($promo)) {
                $player->sendForm(new PromoCreateForm("module.admin.create.message.error.created"));
                return;
            }

            if (!preg_match("/^[a-z0-9]+$/i", $promo)) {
                $player->sendForm(new PromoCreateForm("module.admin.create.message.error.format"));
                return;
            }

            if (strlen($promo) < $promoSettings["minimum_length"] || strlen($promo) > $promoSettings["maximum_length"]) {
                $player->sendForm(new PromoCreateForm("module.admin.create.message.error.length"));
                return;
            }

            if ($type) {
                //TODO: Create PromoCreateTemporaryForm
            } else {
                //TODO: Create PromoCreateUsesLimitedForm
            }
        });
        $this->setTitle($queryHelper->getTranslatedString("module.admin.create.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.admin.create.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.admin.create.form.input.text"), $queryHelper->getTranslatedString("module.admin.create.form.input.placeholder"), "", "promo");
        $this->addToggle($queryHelper->getTranslatedString("module.admin.create.form.toggle.text"), null, "type");
    }

}