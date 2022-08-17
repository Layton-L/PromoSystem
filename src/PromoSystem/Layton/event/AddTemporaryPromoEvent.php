<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

class AddTemporaryPromoEvent extends PromoEvent {

    public function __construct(string $promo, private int $actionTime, private int $amount) {
        parent::__construct($promo);
    }

    public function getActionTime(): int {
        return $this->actionTime;
    }

    public function getAmount(): int {
        return $this->amount;
    }

}