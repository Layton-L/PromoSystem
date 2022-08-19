<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo\change;

use PromoSystem\Layton\event\promo\PromoChangeDataEvent;

class PromoChangeUsesEvent extends PromoChangeDataEvent {

    public function __construct(string $promo, private int $uses) {
        parent::__construct($promo);
    }

    public function getUses(): int {
        return $this->uses;
    }

}