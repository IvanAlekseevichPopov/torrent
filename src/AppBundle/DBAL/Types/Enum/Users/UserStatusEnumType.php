<?php

namespace AppBundle\DBAL\Types\Enum\Users;

use AppBundle\DBAL\Types\Enum\EnumTypeAbstract;

class UserStatusEnumType extends EnumTypeAbstract
{
    const STATUS_NOT_CONFIRMED = 'STATUS_NOT_CONFIRMED';
    const STATUS_CONFIRMED     = 'STATUS_CONFIRMED';
    const STATUS_DELETED       = 'STATUS_DELETED';
    const STATUS_BANNED        = 'STATUS_BANNED';

    /** @var array */
    protected static $choices = [
        self::STATUS_NOT_CONFIRMED => 'Не подтверждён',
        self::STATUS_CONFIRMED     => 'Подтвержден',
        self::STATUS_DELETED       => 'Неактивен (удалён)',
        self::STATUS_BANNED        => 'Заблокирован'
    ];
}
