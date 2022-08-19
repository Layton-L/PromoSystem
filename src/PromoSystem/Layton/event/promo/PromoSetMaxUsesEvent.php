<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo;

class PromoSetMaxUsesEvent extends PromoEvent {

    public function __construct(string $promo, private int $maxUses) {
        parent::__construct($promo);
    }

    public function getMaxUses(): int {
        return $this->maxUses;
    }

}