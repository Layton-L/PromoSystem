<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo;

use PromoSystem\Layton\event\BaseEvent;

abstract class PromoEvent extends BaseEvent {

    public function __construct(private string $promo) {
        parent::__construct();
    }

    public function getPromo(): string {
        return $this->promo;
    }

}