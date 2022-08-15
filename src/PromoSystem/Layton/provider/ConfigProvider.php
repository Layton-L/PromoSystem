<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use Exception;
use pocketmine\utils\Config;
use PromoSystem\Layton\PromoSystem;

class ConfigProvider implements Provider {

    private Config $config;

    public function __construct(PromoSystem $plugin, int $type) {
        $format = array_flip(Config::$formats)[$type];
        $this->config = new Config($plugin->getDataFolder() . "database." . $format, $type);
    }

    public function isCreated(string $promoCode): bool {
        return $this->config->exists($promoCode);
    }

    public function create(string $promoCode, int $amount, int $maxCountUses, int $actionTime): bool {
        try {
            $this->config->set($promoCode, [$amount, 0, $maxCountUses, $actionTime]);
            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function delete(string $promoCode): bool {
        try {
            $this->config->remove($promoCode);
            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getAmount(string $promoCode): int {
        return $this->config->get($promoCode)[0];
    }

    public function getCountUses(string $promoCode): int {
        return $this->config->get($promoCode)[1];
    }

    public function setCountUses(string $promoCode, int $countUses): bool {
        try {
            $this->config->set($promoCode, [
                $this->getAmount($promoCode),
                $countUses,
                $this->getMaxCountUses($promoCode),
                $this->getActionTime($promoCode)
            ]);
            $this->config->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getMaxCountUses(string $promoCode): int {
        return $this->config->get($promoCode)[2];
    }

    public function getActionTime(string $promoCode): int {
        return $this->config->get($promoCode)[3];
    }

    public function getPromoCodes(): array {
        $promoCodes = [];
        foreach ($this->config->getAll() as $promocode => $data) {
            $promoCodes[] = $promocode;
        }

        return $promoCodes;
    }

}