<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

abstract class PromoEvent extends Event implements Cancellable {

    use CancellableTrait;

    public function __construct(private string $promo) {

    }

    public function getPromo(): string {
        return $this->promo;
    }

}