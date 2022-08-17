<?php

declare(strict_types = 1);

namespace PromoSystem\Layton\response;

class Response {

    public function __construct(private int $code) {

    }

    public function getCode(): int {
        return $this->code;
    }

}