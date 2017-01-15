<?php

namespace AppBundle\DBAL\Types\Enum\Users;

use AppBundle\DBAL\Types\Enum\EnumTypeAbstract;

class UserAdditionalGenderEnumType extends EnumTypeAbstract
{
    const STATUS_MALE = 1;
    const STATUS_FEMALE = 2;

    /** @var array */
    protected static $choices = [
        self::STATUS_MALE => 'Мужской',
        self::STATUS_FEMALE => 'Женский',
    ];
}
