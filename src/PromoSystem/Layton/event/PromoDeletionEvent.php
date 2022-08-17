<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

class PromoDeletionEvent extends PromoEvent {

    public function __construct(string $promo, private string $promoType) {
        parent::__construct($promo);
    }

    public function getPromoType(): string {
        return $this->promoType;
    }

}