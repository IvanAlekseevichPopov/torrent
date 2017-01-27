<?php

declare(strict_types = 1);

namespace AppBundle\DBAL\Types;

use Doctrine\DBAL\Platforms as DoctrineDBALPlatforms;
use Doctrine\DBAL\Types\Type;

class IpType extends Type
{
    /**
     * @inheritdoc
     *
     * @param string                                 $value
     * @param DoctrineDBALPlatforms\AbstractPlatform $platform
     *
     * @return integer
     */
    public function convertToDatabaseValue($value, DoctrineDBALPlatforms\AbstractPlatform $platform)
    {
        return ip2long($value);
    }

    /**
     * @inheritdoc
     *
     * @param array                                  $fieldDeclaration
     * @param DoctrineDBALPlatforms\AbstractPlatform $platform
     *
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, DoctrineDBALPlatforms\AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @inheritdoc
     *
     * @param DoctrineDBALPlatforms\AbstractPlatform $platform
     *
     * @return boolean
     */
    public function requiresSQLCommentHint(DoctrineDBALPlatforms\AbstractPlatform $platform)
    {
        return true;
    }

    /**
     * @inheritdoc
     *
     * @param integer                                $value
     * @param DoctrineDBALPlatforms\AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToPHPValue($value, DoctrineDBALPlatforms\AbstractPlatform $platform)
    {
        return long2ip($value);
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getName()
    {
        return 'IpType';
    }
}
