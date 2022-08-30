<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\form\admin;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;

class PromoAdminForm extends SimpleForm {

    public function __construct(string $error = null) {
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();

        parent::__construct(function (Player $player, int $data = null) use ($queryHelper) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    $player->sendForm(new PromoCreateForm());
                    break;
                case 1:
                    $player->sendForm(new PromoDeleteForm());
                    break;
                case 2:
                    $player->sendForm(new PromoInfoForm());
                    break;
                case 3:
                    //TODO: Create PromoViewAllForm
                    break;
            }
        });

        $this->setTitle($queryHelper->getTranslatedString("module.admin.simple.form.title"));

        $this->addButton($queryHelper->getTranslatedString("module.admin.simple.form.button.create"));
        $this->addButton($queryHelper->getTranslatedString("module.admin.simple.form.button.delete"));
        $this->addButton($queryHelper->getTranslatedString("module.admin.simple.form.button.info"));
        $this->addButton($queryHelper->getTranslatedString("module.admin.simple.form.button.view"));
    }

}