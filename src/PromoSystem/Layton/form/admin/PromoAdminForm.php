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

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use PromoSystem\Layton\form\admin\user\UserManagementForm;
use PromoSystem\Layton\PromoSystem;

class PromoAdminForm extends SimpleForm {

    public function __construct() {
        parent::__construct(function (Player $player, int $data = null) {
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
                    $player->sendForm(new PromoListForm());
                    break;
                case 4:
                    $player->sendForm(new UserManagementForm());
                    break;
            }
        });
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();
        $this->setTitle($queryHelper->getCurrentTranslation("module.admin.simple.form.title"));

        $this->addButton($queryHelper->getCurrentTranslation("module.admin.simple.form.button.create"));
        $this->addButton($queryHelper->getCurrentTranslation("module.admin.simple.form.button.delete"));
        $this->addButton($queryHelper->getCurrentTranslation("module.admin.simple.form.button.info"));
        $this->addButton($queryHelper->getCurrentTranslation("module.admin.simple.form.button.view"));
        $this->addButton($queryHelper->getCurrentTranslation("module.admin.simple.form.button.user_management"));
    }

}
