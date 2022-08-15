<?php

declare(strict_types = 1);

namespace PromoSystem\Layton;

use PromoSystem\Layton\provider\Provider;

class DataManager {

    public function __construct(private Provider $provider) {

    }

}