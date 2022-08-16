<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use pocketmine\player\Player;

interface Provider {

    public function isCreated(string $promo): bool;

    public function isTemporary(string $promo): bool;

    public function isUsesLimited(string $promo): bool;

    public function createTemporary(string $promo, int $actionTime, int $amount): bool;

    public function createUsesLimited(string $promo, int $maxUses, int $amount): bool;

    public function delete(string $promo): bool;

    public function getUses(string $promo): int;

    public function setUses(string $promo, int $uses): bool;

    public function getMaxUses(string $promo): ?int;

    public function setMaxUses(string $promo, int $maxUses): bool;

    public function getActionTime(string $promo): ?int;

    public function setActionTime(string $promo, int $actionTime);

    public function getAmount(string $promo): int;

    public function setAmount(string $promo, int $amount): bool;

    public function isActivatedByUser(Player $player, string $promo): bool;

    public function addToUser(Player $player, string $promo): bool;

    public function deleteFromUser(Player $player, string $promo): bool;

}