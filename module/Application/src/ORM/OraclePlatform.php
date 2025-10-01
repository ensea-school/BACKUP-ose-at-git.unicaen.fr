<?php

namespace Application\ORM;

use Doctrine\DBAL\Platforms\OraclePlatform as DoctrineOraclePlatform;

class OraclePlatform extends DoctrineOraclePlatform
{
    public function getMaxIdentifierLength()
    {
        return 100;
    }

}
