<?php

declare(strict_types = 1);

namespace PromoSystem\Layton;

use PromoSystem\Layton\event\PromoCreationEvent;
use PromoSystem\Layton\event\PromoDeletionEvent;
use PromoSystem\Layton\event\PromoSetActionTimeEvent;
use PromoSystem\Layton\event\PromoSetAmountEvent;
use PromoSystem\Layton\event\PromoSetCreationTimeEvent;
use PromoSystem\Layton\event\PromoSetMaxUsesEvent;
use PromoSystem\Layton\event\PromoSetUsesEvent;
use PromoSystem\Layton\provider\Provider;
use PromoSystem\Layton\response\Response;
use PromoSystem\Layton\types\CodeTypes;
use PromoSystem\Layton\types\PromoTypes;

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
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function isUsesLimited(string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->isUsesLimited($promo);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function createTemporary(string $promo, int $actionTime, int $amount): bool|Response {
        if (!$this->provider->isCreated($promo)) {
            $event = new PromoCreationEvent($promo, PromoTypes::TEMPORARY);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_CREATION_CANCELLED);
            }

            return $this->provider->createTemporary($promo, $actionTime, $amount);
        } else {
            return new Response(CodeTypes::PROMO_ALREADY_EXISTS);
        }
    }

    public function createUsesLimited(string $promo, int $maxUses, int $amount): bool|Response {
        if (!$this->provider->isCreated($promo)) {
            $event = new PromoCreationEvent($promo, PromoTypes::USES_LIMITED);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_CREATION_CANCELLED);
            }

            return $this->provider->createUsesLimited($promo, $maxUses, $amount);
        } else {
            return new Response(CodeTypes::PROMO_ALREADY_EXISTS);
        }
    }

    public function delete(string $promo): bool|Response {
        if ($this->provider->isCreated($promo)) {
            $type = $this->provider->isTemporary($promo) ? PromoTypes::TEMPORARY : PromoTypes::USES_LIMITED;

            $event = new PromoDeletionEvent($promo, $type);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_DELETION_CANCELLED);
            }

            return $this->provider->delete($promo);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function getUses(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getUses($promo);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function setUses(string $promo, int $uses): bool|Response {
        if ($this->provider->isCreated($promo)) {
            $event = new PromoSetUsesEvent($promo, $uses);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_CHANGE_DATA_CANCELLED);
            }

            return $this->provider->setUses($promo, $uses);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function getMaxUses(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getMaxUses($promo);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function setMaxUses(string $promo, int $maxUses): bool|Response {
        if ($this->provider->isCreated($promo)) {
            if ($this->provider->isTemporary($promo)) {
                return new Response(CodeTypes::PROMO_INVALID_TYPE);
            }
            $event = new PromoSetMaxUsesEvent($promo, $maxUses);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_CHANGE_DATA_CANCELLED);
            }

            return $this->provider->setMaxUses($promo, $maxUses);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function getCreationTime(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getCreationTime($promo);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function setCreationTime(string $promo, int $creationTime): bool|Response {
        if ($this->provider->isCreated($promo)) {
            $event = new PromoSetCreationTimeEvent($promo, $creationTime);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_CHANGE_DATA_CANCELLED);
            }

            return $this->provider->setCreationTime($promo, $creationTime);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function getActionTime(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getActionTime($promo);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function setActionTime(string $promo, int $actionTime): bool|Response {
        if ($this->provider->isCreated($promo)) {
            if ($this->provider->isUsesLimited($promo)) {
                return new Response(CodeTypes::PROMO_INVALID_TYPE);
            }
            $event = new PromoSetActionTimeEvent($promo, $actionTime);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_CHANGE_DATA_CANCELLED);
            }

            return $this->provider->setActionTime($promo, $actionTime);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function getAmount(string $promo): int|Response {
        if ($this->provider->isCreated($promo)) {
            return $this->provider->getAmount($promo);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

    public function setAmount(string $promo, int $amount): bool|Response {
        if ($this->provider->isCreated($promo)) {
            $event = new PromoSetAmountEvent($promo, $amount);
            $event->call();

            if ($event->isCancelled()) {
                return new Response(CodeTypes::PROMO_CHANGE_DATA_CANCELLED);
            }

            return $this->provider->setAmount($promo, $amount);
        } else {
            return new Response(CodeTypes::PROMO_NOT_CREATED);
        }
    }

}