<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event\user;

use pocketmine\player\Player;

class UserUnactivatedPromoEvent extends UserEvent {

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