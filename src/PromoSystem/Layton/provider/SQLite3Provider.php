<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use PromoSystem\Layton\PromoSystem;
use SQLite3;

class SQLite3Provider implements Provider {

    private SQLite3 $database;

    public function __construct(PromoSystem $plugin) {
        $this->database = new SQLite3($plugin->getDataFolder() . "database.db");

        $this->database->exec(stream_get_contents($plugin->getResource("schemas/promos.sql")));
        $this->database->exec("PRAGMA journal_mode=WAL;");
        $this->database->exec("PRAGMA synchronous=OFF;");
    }

}