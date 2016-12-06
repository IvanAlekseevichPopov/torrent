<?php

namespace AppBundle\DBAL\Types\Enum\Users;

use AppBundle\DBAL\Types\Enum\EnumTypeAbstract;

class UserAdditionalChildStatusEnumType extends EnumTypeAbstract
{
    const STATUS_NO_CHILDREN = 0;
    const STATUS_ONE_CHILD = 1;
    const STATUS_TWO_CHILDREN = 2;
    const STATUS_THREE_OR_MORE = 3;

    /** @var array */
    protected static $choices = [
        self::STATUS_NO_CHILDREN => 'Нет',
        self::STATUS_ONE_CHILD => '1',
        self::STATUS_TWO_CHILDREN => '2',
        self::STATUS_THREE_OR_MORE => '3 и более',
    ];
}
