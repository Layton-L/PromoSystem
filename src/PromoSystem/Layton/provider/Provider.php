<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\provider;

use pocketmine\player\Player;

interface Provider {

    public function isCreated(string $promoCode): bool;

    public function isCreator(Player $player): bool;

    public function create(Player $player, string $promoCode, int $amount): bool;

    public function delete(string $promoCode): bool;

    public function getData(string $promoCode): array;

    public function getDataByPlayer(Player $player): array;

}