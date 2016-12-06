<?php

/**
 * Ценоанализатор
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */

declare(strict_types = 1);

namespace AppBundle\DBAL\Types\Enum\Users;

use AppBundle\DBAL\Types\Enum\EnumTypeAbstract;

/**
 * Типы операций с балансами
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */
class UserBalanceOperationsEnumType extends EnumTypeAbstract
{
    const BALANCE_OPERATION_CREATE   = 'create';
    const BALANCE_OPERATION_DECREASE = 'decrease';
    const BALANCE_OPERATION_INCREASE = 'increase';

    /** @var array */
    protected static $choices = [
        self::BALANCE_OPERATION_CREATE   => 'Создание',
        self::BALANCE_OPERATION_DECREASE => 'Пополнение',
        self::BALANCE_OPERATION_INCREASE => 'Списание'
    ];
}
