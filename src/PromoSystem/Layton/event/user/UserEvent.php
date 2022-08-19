<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\user;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

abstract class UserEvent extends Event implements Cancellable {

    use CancellableTrait;

    public function __construct(private string $promo) {

    }

    public function getPromo(): string {
        return $this->promo;
    }

}