<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use Exception;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use PromoSystem\Layton\PromoSystem;

class ConfigProvider implements Provider {

    private Config $promos;

    private Config $users;

    public function __construct(PromoSystem $plugin, int $type) {
        $format = array_flip(Config::$formats)[$type];

        $this->promos = new Config($plugin->getDataFolder() . "promos." . $format, $type);
        $this->users = new Config($plugin->getDataFolder() . "users." . $format, $type);
    }

    public function isCreated(string $promo): bool {
        return $this->promos->exists($promo);
    }

    public function isTemporary(string $promo): bool {
        $promo = $this->promos->get($promo);
        return $promo[1] === -1;
    }

    public function isUsesLimited(string $promo): bool {
        $promo = $this->promos->get($promo);
        return $promo[2] === -1;
    }

    public function createTemporary(string $promo, int $actionTime, int $amount): bool {
        try {
            $this->promos->set($promo, [0, -1, time(), $actionTime, $amount]);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function createUsesLimited(string $promo, int $maxUses, int $amount): bool {
        try {
            $this->promos->set($promo, [0, $maxUses, time(), -1, $amount]);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function delete(string $promo): bool {
        try {
            $this->promos->remove($promo);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function getUses(string $promo): int {
        return $this->promos->get($promo)[0];
    }

    public function setUses(string $promo, int $uses): bool {
        try {
            $data = $this->promos->get($promo);
            $data[0] = $uses;

            $this->promos->set($promo, $data);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function getMaxUses(string $promo): int {
        return $this->promos->get($promo)[1];
    }

    public function setMaxUses(string $promo, int $maxUses): bool {
        try {
            $data = $this->promos->get($promo);
            $data[1] = $maxUses;

            $this->promos->set($promo, $data);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function getCreationTime(string $promo): int {
        return $this->promos->get($promo)[2];
    }

    public function setCreationTime(string $promo, int $creationTime): bool {
        try {
            $data = $this->promos->get($promo);
            $data[2] = $creationTime;

            $this->promos->set($promo, $data);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function getActionTime(string $promo): int {
        return $this->promos->get($promo)[3];
    }

    public function setActionTime(string $promo, int $actionTime): bool {
        try {
            $data = $this->promos->get($promo);
            $data[3] = $actionTime;

            $this->promos->set($promo, $data);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function getAmount(string $promo): int {
        return $this->promos->get($promo)[4];
    }

    public function setAmount(string $promo, int $amount): bool {
        try {
            $data = $this->promos->get($promo);
            $data[4] = $amount;

            $this->promos->set($promo, $data);
            $this->promos->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function isActivatedByUser(Player $player, string $promo): bool {
        $name = strtolower($player->getName());
        $promos = $this->users->get($name);

        return in_array($promo, $promos);
    }

    public function addToUser(Player $player, string $promo): bool {
        try {
            $name = strtolower($player->getName());

            $promos = $this->users->get($name);
            $promos[] = $promo;

            $this->users->set($name, $promos);
            $this->users->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function deleteFromUser(Player $player, string $promo): bool {
        try {
            $name = strtolower($player->getName());
            $promos = $this->users->get($name);

            $key = array_search($promo, $promos);
            unset($promos[$key]);

            $this->users->set($name, $promos);
            $this->users->save();

            return true;
        } catch (Exception) {
            return false;
        }
    }

}