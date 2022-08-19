<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo;

use pocketmine\event\Event;

abstract class PromoChangeDataEvent extends Event {

    public function __construct(private string $promo) {

    }

    public function getPromo(): string {
        return $this->promo;
    }

}