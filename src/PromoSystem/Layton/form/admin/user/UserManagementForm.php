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

namespace PromoSystem\Layton\form\admin\user;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;

class UserManagementForm extends SimpleForm {

    public function __construct() {
        parent::__construct(function (Player $player, int $data = null) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    //TODO: Create UserActivatePromoForm
                    break;
                case 1:
                    //TODO: Create UserDeactivatePromoForm
                    break;
                case 2:
                    //TODO: Create UserPromoListForm
                    break;
            }
        });
        $queryHelper = PromoSystem::getInstance()->getTranslationManager()->getQueryHelper();
        $this->setTitle($queryHelper->getCurrentTranslation("module.admin.user_management.form.title"));

        $this->addButton($queryHelper->getCurrentTranslation("module.admin.user_management.form.button.activate"));
        $this->addButton($queryHelper->getCurrentTranslation("module.admin.user_management.form.button.deactivate"));
        $this->addButton($queryHelper->getCurrentTranslation("module.admin.user_management.form.button.view"));
    }

}
