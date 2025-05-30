<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatusEnum extends Enum
{
    const Pending   = 'pending';
    const Shipped   = 'shipped';
    const Delivered = 'delivered';
    const Canceled  = 'canceled';

    public static function getDescription($value): string
    {
        return match ($value) {
            self::Pending   => 'Pending',
            self::Shipped   => 'Shipped',
            self::Delivered => 'Delivered',
            self::Canceled  => 'Canceled',
            default         => $value,
        };
    }
}
