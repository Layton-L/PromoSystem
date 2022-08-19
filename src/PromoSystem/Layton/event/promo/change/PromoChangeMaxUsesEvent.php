<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo\change;

use PromoSystem\Layton\event\promo\PromoChangeDataEvent;

class PromoChangeMaxUsesEvent extends PromoChangeDataEvent {

    public function __construct(string $promo, private int $maxUses) {
        parent::__construct($promo);
    }

    public function getMaxUses(): int {
        return $this->maxUses;
    }

}