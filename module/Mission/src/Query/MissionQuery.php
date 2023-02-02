<?php

namespace Mission\Query;

use Doctrine\DBAL\Result;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Parser;

class MissionQuery extends AbstractQuery
{
    protected $dql = "

    ";



    public function getSQL()
    {
        // TODO: Implement getSQL() method.


        $parser = new Parser();
    }



    protected function _doExecute()
    {
        // TODO: Implement _doExecute() method.
    }

}