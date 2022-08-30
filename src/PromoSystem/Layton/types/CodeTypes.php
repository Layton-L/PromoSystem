<?php

/**
 * PromoSystem, a promo codes system plugin for PocketMine-MP
 * Copyright (c) 2022 Layton-L  < https://github.com/Layton-L >
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * PromoSystem is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 */

declare(strict_types = 1);

namespace PromoSystem\Layton\types;

class CodeTypes {

    public const PROMO_UNACTIVATED_CANCELLED = -1;

    public const PROMO_NOT_ACTIVATED = -2;

    public const PROMO_ACTIVATED_CANCELLED = -3;

    public const PROMO_ALREADY_ACTIVATED = -4;

    public const PROMO_NOT_CREATED = -5;

    public const PROMO_ALREADY_EXISTS = -6;

    public const PROMO_CREATION_CANCELLED = -7;

    public const PROMO_DELETION_CANCELLED = -8;

    public const PROMO_INVALID_TYPE = -9;

}