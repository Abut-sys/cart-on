<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaymentStatusEnum extends Enum
{
    const Pending   = 'pending';
    const Completed = 'completed';
    const Failed    = 'failed';

    public static function getDescription($value): string
    {
        return match ($value) {
            self::Pending   => 'Unpaid',
            self::Completed => 'Paid',
            self::Failed    => 'Failed',
            default         => $value,
        };
    }
}
