<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use PromoSystem\Layton\PromoSystem;
use SQLite3;

class SQLite3Provider implements Provider {

    private SQLite3 $database;

    public function __construct(PromoSystem $plugin) {
        $this->database = new SQLite3($plugin->getDataFolder() . "database.db");

        $this->database->exec(stream_get_contents($plugin->getResource("schemas/promocodes.sql")));
        $this->database->exec("PRAGMA journal_mode=WAL;");
        $this->database->exec("PRAGMA synchronous=OFF;");
    }

    public function isCreated(string $promoCode): bool {
        $result = $this->database->query("SELECT * FROM `promocodes` WHERE `promocode` = '" . $promoCode ."'");
        return !empty($result->fetchArray(SQLITE3_ASSOC));
    }

    public function create(string $promoCode, int $amount, int $maxCountUses, int $actionTime): bool {
        $statement = $this->database->prepare("INSERT INTO `promocodes` (`promocode`, `amount`, `max_count_uses`, `action_time`) VALUES (:promocode, :amount, :max_count_uses, :action_time)");

        $statement->bindValue(":promocode", $promoCode);
        $statement->bindValue(":amount", $amount);
        $statement->bindValue(":max_count_uses", $maxCountUses);
        $statement->bindValue(":action_time", $actionTime);

        $statement->execute();
        return $this->database->changes() === 1;
    }

    public function delete(string $promoCode): bool {
        $this->database->query("DELETE FROM `promocodes` WHERE `promocode` = '" . $promoCode ."'");
        return $this->database->changes() === 1;
    }

    public function getAmount(string $promoCode): int {
        $result = $this->database->query("SELECT `amount` FROM `promocodes` WHERE `promocode` = '" . $promoCode ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["amount"];
    }

    public function getCountUses(string $promoCode): int {
        $result = $this->database->query("SELECT `count_uses` FROM `promocodes` WHERE `promocode` = '" . $promoCode ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["count_uses"];
    }

    public function getMaxCountUses(string $promoCode): int {
        $result = $this->database->query("SELECT `max_count_uses` FROM `promocodes` WHERE `promocode` = '" . $promoCode ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["max_count_uses"];
    }

    public function getActionTime(string $promoCode): int {
        $result = $this->database->query("SELECT `action_time` FROM `promocodes` WHERE `promocode` = '" . $promoCode ."'");
        return $result->fetchArray(SQLITE3_ASSOC)["action_time"];
    }

    public function getPromoCodes(): array {
        $result = $this->database->query("SELECT `promocode` FROM `promocodes`");
        var_dump($result->fetchArray(SQLITE3_ASSOC));

        return [];
    }

}