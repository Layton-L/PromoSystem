<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

class PromoSetCreationTimeEvent extends PromoEvent {

    public function __construct(string $promo, private int $creationTime) {
        parent::__construct($promo);
    }

    public function getCreationTime(): int {
        return $this->creationTime;
    }

}