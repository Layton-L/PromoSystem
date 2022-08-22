<?php


declare(strict_types=1);

namespace PromoSystem\Layton\form\admin;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;
use PromoSystem\Layton\response\Response;

class PromoCreateTemporaryForm extends CustomForm {

    public function __construct(string $promo, int $amount, string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, array $data = null) use ($promo, $amount, $queryHelper) {
            if ($data === null) return;

            $dataManager = PromoSystem::getInstance()->getDataManager();
            $actionTime = trim($data["action_time"]);

            if ($actionTime === "") {
                $player->sendForm(new PromoCreateTemporaryForm($promo, $amount, "module.admin.create.temporary.form.input.empty"));
                return;
            }

            if (!(ctype_digit($actionTime) && (int) $actionTime > 0)) {
                $player->sendForm(new PromoCreateTemporaryForm($promo, $amount, "module.admin.create.message.error.action_time"));
                return;
            }

            if ($dataManager->createTemporary($promo, (int) $actionTime, $amount) instanceof Response) {
                $player->sendForm(new PromoCreateTemporaryForm($promo, $amount, "module.admin.create.message.error.cancelled"));
                return;
            }

            $player->sendMessage($queryHelper->getTranslatedString("module.admin.create.message.successful"));
        });
        $this->setTitle($queryHelper->getTranslatedString("module.admin.create.temporary.form.title"));

        if ($error === null) {
            $this->addLabel($queryHelper->getTranslatedString("module.admin.create.temporary.form.label"));
        } else {
            $this->addLabel($queryHelper->getTranslatedString($error));
        }

        $this->addInput($queryHelper->getTranslatedString("module.admin.create.temporary.form.input.text"), $queryHelper->getTranslatedString("module.admin.create.temporary.form.input.placeholder"), "", "action_time");
    }

}