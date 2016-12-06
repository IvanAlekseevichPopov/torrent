<?php

namespace AppBundle\DBAL\Types\Enum\Users;

use AppBundle\DBAL\Types\Enum\EnumTypeAbstract;

class UserAdditionalMaritalStatusEnumType extends EnumTypeAbstract
{
    const STATUS_MARRIED = 1;
    const STATUS_NOT_MARRIED = 2;

    /** @var array */
    protected static $choices = [
        self::STATUS_MARRIED => 'Женат/Замужем',
        self::STATUS_NOT_MARRIED     => 'Холост/Не замужем',
    ];
}