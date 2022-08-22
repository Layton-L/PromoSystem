<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo\change;

class PromoChangeActionTimeEvent extends PromoChangeDataEvent {

    public function __construct(string $promo, private int $actionTime) {
        parent::__construct($promo);
    }

    public function getActionTime(): int {
        return $this->actionTime;
    }

}