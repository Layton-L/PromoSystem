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

interface Provider {

    public function isCreated(string $promo): bool;

    public function isTemporary(string $promo): bool;

    public function isUsesLimited(string $promo): bool;

    public function createTemporary(string $promo, int $actionTime, int $amount): bool;

    public function createUsesLimited(string $promo, int $maxUses, int $amount): bool;

    public function delete(string $promo): bool;

    public function getAllPromos(): array;

    public function getUses(string $promo): int;

    public function setUses(string $promo, int $uses): bool;

    public function getMaxUses(string $promo): int;

    public function setMaxUses(string $promo, int $maxUses): bool;

    public function getCreationTime(string $promo): int;

    public function setCreationTime(string $promo, int $creationTime): bool;

    public function getActionTime(string $promo): int;

    public function setActionTime(string $promo, int $actionTime): bool;

    public function getAmount(string $promo): int;

    public function setAmount(string $promo, int $amount): bool;

    public function isActivatedByUser(Player $player, string $promo): bool;

    public function addToUser(Player $player, string $promo): bool;

    public function deleteFromUser(Player $player, string $promo): bool;

    public function getUserPromos(Player $player): array;

}