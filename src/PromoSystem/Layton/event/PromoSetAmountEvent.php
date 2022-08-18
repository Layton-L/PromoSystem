<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

class PromoSetAmountEvent extends PromoEvent {

    public function __construct(string $promo, private int $amount) {
        parent::__construct($promo);
    }

    public function getAmount(): int {
        return $this->amount;
    }

}