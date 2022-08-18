<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

use pocketmine\player\Player;

class PromoActivatedEvent extends PromoEvent {

    public function __construct(string $promo, private Player $user, private string $promoType) {
        parent::__construct($promo);
    }

    public function getUser(): Player {
        return $this->user;
    }

    public function getPromoType(): string {
        return $this->promoType;
    }

}