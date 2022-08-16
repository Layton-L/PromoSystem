<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use Exception;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use PromoSystem\Layton\PromoSystem;

class ConfigProvider implements Provider {

    private Config $config;

    private array $data;

    public function __construct(PromoSystem $plugin, int $type) {
        $format = array_flip(Config::$formats)[$type];
        $this->config = new Config($plugin->getDataFolder() . "database." . $format, $type, [
            "promos" => [],
            "users" => []
        ]);

        $this->data = $this->config->getAll();
    }

    public function isCreated(string $promo): bool {
        $promos = $this->data["promos"];
        return isset($promos[$promo]);
    }

    public function isTemporary(string $promo): bool {
        $promos = $this->data["promos"];
        return $promos[$promo][1] === -1;
    }

    public function isUsesLimited(string $promo): bool {
        $promos = $this->data["promos"];;
        return $promos[$promo][2] === -1;
    }

    public function createTemporary(string $promo, int $actionTime, int $amount): bool {
        try {
            $this->data["promos"][$promo] = [0, -1, $actionTime, $amount];
            $this->config->setAll($this->data);

            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function createUsesLimited(string $promo, int $maxUses, int $amount): bool {
        try {
            $this->data["promos"][$promo] = [0, $maxUses, -1, $amount];
            $this->config->setAll($this->data);

            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function delete(string $promo): bool {
        try {
            unset($this->data["promos"][$promo]);
            $this->config->setAll($this->data);

            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getUses(string $promo): int {
        return $this->data["promos"][$promo][0];
    }

    public function setUses(string $promo, int $uses): bool {
        try {
            $this->data["promos"][$promo][0] = $uses;
            $this->config->setAll($this->data);

            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getMaxUses(string $promo): int {
        return $this->data["promos"][$promo][1];
    }

    public function setMaxUses(string $promo, int $maxUses): bool {
        try {
            $this->data["promos"][$promo][1] = $maxUses;
            $this->config->setAll($this->data);

            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getActionTime(string $promo): int {
        return $this->data["promos"][$promo][2];
    }

    public function setActionTime(string $promo, int $actionTime): bool {
        try {
            $this->data["promos"][$promo][2] = $actionTime;
            $this->config->setAll($this->data);

            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getAmount(string $promo): int {
        return $this->data["promos"][$promo][3];
    }

    public function setAmount(string $promo, int $amount): bool {
        try {
            $this->data["promos"][$promo][3] = $amount;
            $this->config->setAll($this->data);

            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function isActivatedByUser(Player $player, string $promo): bool {
        $users = $this->data["users"];
        return in_array($promo, $users[strtolower($player->getName())]);
    }

    public function addToUser(Player $player, string $promo): bool {
        try {
            $name = strtolower($player->getName());
            $this->data["users"][$name][] = $promo;

            $this->config->setAll($this->data);
            $this->config->save();

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function deleteFromUser(Player $player, string $promo): bool {
        try {
            $name = strtolower($player->getName());
            $key = array_search($promo, $this->data["users"][$name]);
            unset($this->data["users"][$name][$key]);

            $this->config->setAll($this->data);
            $this->config->save();

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

}