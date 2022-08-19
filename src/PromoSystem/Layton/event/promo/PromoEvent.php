<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\promo;

use PromoSystem\Layton\event\CancellableEvent;

abstract class PromoEvent extends CancellableEvent {

    public function __construct(private string $promo) {
        parent::__construct();
    }

    public function getPromo(): string {
        return $this->promo;
    }

}