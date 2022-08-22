<?php


declare(strict_types=1);

namespace PromoSystem\Layton\form\admin;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;

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
                $player->sendForm(new PromoCreateUsesLimitedForm($promo, $amount,"module.admin.create.message.error.uses_limited"));
                return;
            }

            $dataManager->createUsesLimited($promo, (int) $max_uses, $amount);
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