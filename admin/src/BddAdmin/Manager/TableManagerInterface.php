<?php

namespace BddAdmin\Manager;

interface TableManagerInterface extends ManagerInterface
{
    public function majSequence(array $data);



    public function isDiff(array $d1, array $d2);

}