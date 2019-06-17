<?php

namespace BddAdmin\Data;


abstract class AbstractInput
{
    /**
     * @param array $config
     *
     * @return mixed
     */
    abstract public function applyConfig(array $config);



    /**
     * @return mixed
     */
    abstract public function lire(AbstractOutput $output);
}