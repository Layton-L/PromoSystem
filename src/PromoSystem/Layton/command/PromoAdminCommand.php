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

namespace PromoSystem\Layton\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use PromoSystem\Layton\form\admin\PromoAdminForm;

class PromoAdminCommand extends Command {

    public const PERMISSION = "promosystem.admin";

    public function __construct(string $name, string $description) {
        parent::__construct($name, $description);
        $this->setPermission(PromoAdminCommand::PERMISSION);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player || !$sender->hasPermission(PromoAdminCommand::PERMISSION)) {
            return;
        }

        if (count($args) > 0) {
            $sender->sendMessage("/promo-admin");
            return;
        }

        $sender->sendForm(new PromoAdminForm());
    }

}