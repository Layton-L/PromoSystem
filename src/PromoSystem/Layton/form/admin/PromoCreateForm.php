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