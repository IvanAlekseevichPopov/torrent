<?php

namespace AppBundle\DBAL\Types\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

abstract class EnumTypeAbstract extends AbstractEnumType
{
    /**
     * Геттер описания значения
     *
     * @param string|integer $type
     *
     * @return string
     */
    public static function getDescription($type)
    {
        $choices = static::getChoices();

        return !empty($choices[$type]) ? $choices[$type] : 'n/d';
    }
}
