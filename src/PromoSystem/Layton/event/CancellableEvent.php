<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

abstract class CancellableEvent extends Event implements Cancellable {

    use CancellableTrait;

    public function __construct() {

    }

}