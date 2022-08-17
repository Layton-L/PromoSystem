<?php

declare(strict_types = 1);

namespace PromoSystem\Layton;

use PromoSystem\Layton\event\PromoCreationEvent;
use PromoSystem\Layton\event\PromoDeletionEvent;
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

}