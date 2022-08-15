<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use pocketmine\player\Player;

interface Provider {

    public function isCreated(string $promoCode): bool;

    public function create(string $promoCode, int $amount): bool;

    public function delete(string $promoCode): bool;

    public function getAmount(string $promoCode): int;

    public function getCountUses(string $promoCode): int;

    public function getMaxCountUses(string $promoCode): int;

    public function getActionTime(string $promoCode): int;

}