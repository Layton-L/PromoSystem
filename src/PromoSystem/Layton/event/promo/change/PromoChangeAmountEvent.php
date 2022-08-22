<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo\change;

class PromoChangeAmountEvent extends PromoChangeDataEvent {

    public function __construct(string $promo, private int $amount) {
        parent::__construct($promo);
    }

    public function getAmount(): int {
        return $this->amount;
    }

}