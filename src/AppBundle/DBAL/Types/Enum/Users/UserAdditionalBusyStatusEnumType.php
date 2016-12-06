<?php

namespace AppBundle\DBAL\Types\Enum\Users;

use AppBundle\DBAL\Types\Enum\EnumTypeAbstract;

class UserAdditionalBusyStatusEnumType extends EnumTypeAbstract
{
    const STATUS_WORKING = 1;
    const STATUS_NOT_WORKING = 2;
    const STATUS_LEARNING = 3;

    /** @var array */
    protected static $choices = [
        self::STATUS_WORKING => 'Трудоустроен',
        self::STATUS_NOT_WORKING => 'Не трудоустроен',
        self::STATUS_LEARNING => 'Учусь'
    ];
}
