<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo\change;

class PromoChangeCreationTimeEvent extends PromoChangeDataEvent {

    public function __construct(string $promo, private int $creationTime) {
        parent::__construct($promo);
    }

    public function getCreationTime(): int {
        return $this->creationTime;
    }

}