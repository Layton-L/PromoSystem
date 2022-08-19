<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo;

class PromoSetActionTimeEvent extends PromoEvent {

    public function __construct(string $promo, private int $actionTime) {
        parent::__construct($promo);
    }

    public function getActionTime(): int {
        return $this->actionTime;
    }

}