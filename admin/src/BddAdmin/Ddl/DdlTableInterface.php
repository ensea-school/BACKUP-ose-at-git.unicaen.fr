<?php

namespace BddAdmin\Ddl;

interface DdlTableInterface extends DdlInterface
{
    public function majSequence(array $data);



    public function isDiff(array $d1, array $d2);

}