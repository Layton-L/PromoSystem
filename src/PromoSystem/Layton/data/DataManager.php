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

namespace PromoSystem\Layton\data;

use pocketmine\player\Player;
use PromoSystem\Layton\event\promo\change\PromoChangeActionTimeEvent;
use PromoSystem\Layton\event\promo\change\PromoChangeAmountEvent;
use PromoSystem\Layton\event\promo\change\PromoChangeCreationTimeEvent;
use PromoSystem\Layton\event\promo\change\PromoChangeMaxUsesEvent;
use PromoSystem\Layton\event\promo\change\PromoChangeUsesEvent;
use PromoSystem\Layton\event\promo\PromoCreationEvent;
use PromoSystem\Layton\event\promo\PromoDeletionEvent;
use PromoSystem\Layton\event\user\UserActivatedPromoEvent;
use PromoSystem\Layton\event\user\UserUnactivatedPromoEvent;
use PromoSystem\Layton\provider\Provider;
use PromoSystem\Layton\response\Response;
use PromoSystem\Layton\enums\CodeType;
use PromoSystem\Layton\enums\PromoType;

class DataManager {

    public function __construct(private Provider $provider) {

    }

    public function isCreated(string $promo): bool {
        return $this->provider->isCreated($promo);
    }

    public function isTemporary(string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->isTemporary($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function isUsesLimited(string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->isUsesLimited($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function createTemporary(string $promo, int $actionTime, int $amount): bool|Response {
        if (!$this->provider->isCreated($promo)) {
            $event = new PromoCreationEvent($promo, PromoType::TEMPORARY);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeType::PROMO_CREATION_CANCELLED);
            }

            return $this->provider->createTemporary($promo, $actionTime, $amount);
        } else {
            return new Response(CodeType::PROMO_ALREADY_EXISTS);
        }
    }

    public function createUsesLimited(string $promo, int $maxUses, int $amount): bool|Response {
        if (!$this->provider->isCreated($promo)) {
            $event = new PromoCreationEvent($promo, PromoType::USES_LIMITED);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeType::PROMO_CREATION_CANCELLED);
            }

            return $this->provider->createUsesLimited($promo, $maxUses, $amount);
        } else {
            return new Response(CodeType::PROMO_ALREADY_EXISTS);
        }
    }

    public function delete(string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            $type = $this->provider->isTemporary($promo) ? PromoType::TEMPORARY : PromoType::USES_LIMITED;

            $event = new PromoDeletionEvent($promo, $type);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeType::PROMO_DELETION_CANCELLED);
            }

            return $this->provider->delete($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function getAllPromos(): array {
        return $this->provider->getAllPromos();
    }

    public function getUses(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getUses($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function setUses(string $promo, int $uses): bool|Response {
        if ($this->provider->isCreated($promo)) {
            (new PromoChangeUsesEvent($promo, $uses))->call();
            return $this->provider->setUses($promo, $uses);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function getMaxUses(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getMaxUses($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function setMaxUses(string $promo, int $maxUses): bool|Response {
        if ($this->provider->isCreated($promo)) {
            if ($this->provider->isTemporary($promo)) {
                return new Response(CodeType::PROMO_INVALID_TYPE);
            }

            (new PromoChangeMaxUsesEvent($promo, $maxUses))->call();
            return $this->provider->setMaxUses($promo, $maxUses);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function getCreationTime(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getCreationTime($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function setCreationTime(string $promo, int $creationTime): bool|Response {
        if ($this->provider->isCreated($promo)) {
            (new PromoChangeCreationTimeEvent($promo, $creationTime))->call();
            return $this->provider->setCreationTime($promo, $creationTime);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function getActionTime(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getActionTime($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function setActionTime(string $promo, int $actionTime): bool|Response {
        if ($this->provider->isCreated($promo)) {
            if ($this->provider->isUsesLimited($promo)) {
                return new Response(CodeType::PROMO_INVALID_TYPE);
            }

            (new PromoChangeActionTimeEvent($promo, $actionTime))->call();
            return $this->provider->setActionTime($promo, $actionTime);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function getAmount(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getAmount($promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function setAmount(string $promo, int $amount): bool|Response {
        if ($this->provider->isCreated($promo)) {
            (new PromoChangeAmountEvent($promo, $amount))->call();
            return $this->provider->setAmount($promo, $amount);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function isActivatedByUser(Player $player, string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->isActivatedByUser($player, $promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function addToUser(Player $player, string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            if ($this->provider->isActivatedByUser($player, $promo)) {
                return new Response(CodeType::PROMO_ALREADY_ACTIVATED);
            }
            $type = $this->provider->isTemporary($promo) ? PromoType::TEMPORARY : PromoType::USES_LIMITED;

            $event = new UserActivatedPromoEvent($player, $promo, $type);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeType::PROMO_ACTIVATED_CANCELLED);
            }

            return $this->provider->addToUser($player, $promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function deleteFromUser(Player $player, string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            if (!$this->provider->isActivatedByUser($player, $promo)) {
                return new Response(CodeType::PROMO_NOT_ACTIVATED);
            }
            $type = $this->provider->isTemporary($promo) ? PromoType::TEMPORARY : PromoType::USES_LIMITED;

            $event = new UserUnactivatedPromoEvent($player, $promo, $type);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeType::PROMO_UNACTIVATED_CANCELLED);
            }

            return $this->provider->deleteFromUser($player, $promo);
        } else {
            return new Response(CodeType::PROMO_NOT_CREATED);
        }
    }

    public function getUserPromos(Player $player): array {
        return $this->provider->getUserPromos($player);
    }

}