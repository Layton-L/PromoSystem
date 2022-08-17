<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

class AddUsesLimitedPromoEvent extends PromoEvent {

    public function __construct(string $promo, private int $maxUses, private int $amount) {
        parent::__construct($promo);
    }

    public function getMaxUses(): int {
        return $this->maxUses;
    }

    public function getAmount(): int {
        return $this->amount;
    }

}