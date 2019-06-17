<?php

namespace BddAdmin\Data;


abstract class AbstractOutput
{
    /**
     * @param array $config
     *
     * @return mixed
     */
    abstract public function applyConfig(array $config);



    /**
     * @param string|null $table
     */
    abstract public function ecrireDebut(?string $table = null);



    /**
     * @param string|null $table
     */
    abstract public function ecrireFin(?string $table = null);



    /**
     * @param string $table
     * @param array  $data
     */
    abstract public function ecrire(string $table, array $data);
}