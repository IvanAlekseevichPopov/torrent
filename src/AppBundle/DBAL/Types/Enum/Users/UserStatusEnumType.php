<?php

namespace AppBundle\DBAL\Types\Enum\Users;

use AppBundle\DBAL\Types\Enum\EnumTypeAbstract;

class UserStatusEnumType extends EnumTypeAbstract
{
    const STATUS_NOT_CONFIRMED = 1;
    const STATUS_CONFIRMED     = 2;
    const STATUS_DELETED       = 3;
    const STATUS_BANNED        = 4;

    /** @var array */
    protected static $choices = [
        self::STATUS_NOT_CONFIRMED => 'Не подтверждён',
        self::STATUS_CONFIRMED     => 'Подтвержден',
        self::STATUS_DELETED       => 'Неактивен (удалён)',
        self::STATUS_BANNED        => 'Заблокирован'
    ];
}
