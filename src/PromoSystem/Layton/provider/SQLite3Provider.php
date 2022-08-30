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

namespace PromoSystem\Layton\provider;

use pocketmine\player\Player;
use PromoSystem\Layton\PromoSystem;
use SQLite3;

class SQLite3Provider implements Provider {

    private SQLite3 $database;

    public function __construct(PromoSystem $plugin) {
        $this->database = new SQLite3($plugin->getDataFolder() . "database.db");

        $this->database->exec(stream_get_contents($plugin->getResource("schemas/promos.sql")));
        $this->database->exec(stream_get_contents($plugin->getResource("schemas/users.sql")));

        $this->database->exec("PRAGMA journal_mode=WAL;");
        $this->database->exec("PRAGMA synchronous=OFF;");
    }

    public function isCreated(string $promo): bool {
        $result = $this->database->query("SELECT * FROM `promos` WHERE `promo` = '" . $promo ."'");
        return !empty($result->fetchArray(SQLITE3_ASSOC));
    }

    public function isTemporary(string $promo): bool {
        $result = $this->database->query("SELECT `max_uses` FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["max_uses"] === -1;
    }

    public function isUsesLimited(string $promo): bool {
        $result = $this->database->query("SELECT `action_time` FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["action_time"] === -1;
    }

    public function createTemporary(string $promo, int $actionTime, int $amount): bool {
        $statement = $this->database->prepare("INSERT INTO `promos` (`promo`, `max_uses`, `creation_time`, `action_time`, `amount`) VALUES (:promo, :max_uses, :creation_time, :action_time, :amount)");

        $statement->bindValue(":promo", $promo);
        $statement->bindValue(":max_uses", -1);
        $statement->bindValue(":creation_time", time());
        $statement->bindValue(":action_time", $actionTime);
        $statement->bindValue(":amount", $amount);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function createUsesLimited(string $promo, int $maxUses, int $amount): bool {
        $statement = $this->database->prepare("INSERT INTO `promos` (`promo`, `max_uses`, `creation_time`, `action_time`, `amount`) VALUES (:promo, :max_uses, :creation_time, :action_time, :amount)");

        $statement->bindValue(":promo", $promo);
        $statement->bindValue(":max_uses", $maxUses);
        $statement->bindValue(":creation_time", time());
        $statement->bindValue(":action_time", -1);
        $statement->bindValue(":amount", $amount);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function delete(string $promo): bool {
        $this->database->query("DELETE FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $this->database->changes() === 1;
    }

    public function getAllPromos(): array {
        $query = $this->database->query("SELECT `promo` FROM `promos`");
        $promos = [];

        $i = 0;
        while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
            $promos[$i] = $result["promo"];
            $i++;
        }

        return $promos;
    }

    public function getUses(string $promo): int {
        $result = $this->database->query("SELECT `uses` FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["uses"];
    }

    public function setUses(string $promo, int $uses): bool {
        $statement = $this->database->prepare("UPDATE `promos` SET `uses` = :uses WHERE `promo` = :promo");

        $statement->bindValue(":promo", $promo);
        $statement->bindValue(":uses", $uses);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function getMaxUses(string $promo): int {
        $result = $this->database->query("SELECT `max_uses` FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["max_uses"];
    }

    public function setMaxUses(string $promo, int $maxUses): bool {
        $statement = $this->database->prepare("UPDATE `promos` SET `max_uses` = :max_uses WHERE `promo` = :promo");

        $statement->bindValue(":promo", $promo);
        $statement->bindValue(":max_uses", $maxUses);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function getCreationTime(string $promo): int {
        $result = $this->database->query("SELECT `creation_time` FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["creation_time"];
    }

    public function setCreationTime(string $promo, int $creationTime): bool {
        $statement = $this->database->prepare("UPDATE `promos` SET `creation_time` = :creation_time WHERE `promo` = :promo");

        $statement->bindValue(":promo", $promo);
        $statement->bindValue(":creation_time", $creationTime);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function getActionTime(string $promo): int {
        $result = $this->database->query("SELECT `action_time` FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["action_time"];
    }

    public function setActionTime(string $promo, int $actionTime): bool {
        $statement = $this->database->prepare("UPDATE `promos` SET `action_time` = :action_time WHERE `promo` = :promo");

        $statement->bindValue(":promo", $promo);
        $statement->bindValue(":action_time", $actionTime);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function getAmount(string $promo): int {
        $result = $this->database->query("SELECT `amount` FROM `promos` WHERE `promo` = '" . $promo ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["amount"];
    }

    public function setAmount(string $promo, int $amount): bool {
        $statement = $this->database->prepare("UPDATE `promos` SET `amount` = :amount WHERE `promo` = :promo");

        $statement->bindValue(":promo", $promo);
        $statement->bindValue(":amount", $amount);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function isActivatedByUser(Player $player, string $promo): bool {
        $name = strtolower($player->getName());
        $statement = $this->database->prepare("SELECT * FROM `users` WHERE `name` = :name AND `promo` = :promo");

        $statement->bindValue(":name", $name);
        $statement->bindValue(":promo", $promo);

        $result = $statement->execute();
        return !empty($result->fetchArray(SQLITE3_ASSOC));
    }

    public function addToUser(Player $player, string $promo): bool {
        $name = strtolower($player->getName());
        $statement = $this->database->prepare("INSERT INTO `users` (`name`, `promo`) VALUES (:name, :promo)");

        $statement->bindValue(":name", $name);
        $statement->bindValue(":promo", $promo);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function deleteFromUser(Player $player, string $promo): bool {
        $name = strtolower($player->getName());
        $statement = $this->database->prepare("DELETE FROM `users` WHERE `name` = :name AND `promo` = :promo");

        $statement->bindValue(":name", $name);
        $statement->bindValue(":promo", $promo);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function getUserPromos(Player $player): array {
        $name = strtolower($player->getName());
        $query = $this->database->query("SELECT `promo` FROM `users` WHERE name = '" . $name . "'");

        $promos = [];
        $i = 0;

        while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
            $promos[$i] = $result["promo"];
            $i++;
        }

        return $promos;
    }

}