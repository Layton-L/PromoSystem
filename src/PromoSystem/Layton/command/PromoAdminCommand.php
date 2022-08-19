<?php

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
        $this->setPermission(PromoCommand::PERMISSION);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player || !$sender->hasPermission(PromoCommand::PERMISSION)) {
            return;
        }

        if (count($args) > 0) {
            $sender->sendMessage("/promo-admin");
            return;
        }

        $sender->sendForm(new PromoAdminForm());
    }

}