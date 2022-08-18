<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

class PromoSetUsesEvent extends PromoEvent {

    public function __construct(string $promo, private int $uses) {
        parent::__construct($promo);
    }

    public function getUses(): int {
        return $this->uses;
    }

}