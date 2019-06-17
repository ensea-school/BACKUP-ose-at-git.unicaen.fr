<?php

namespace BddAdmin\Data;


class VarDumpOutput extends AbstractOutput
{

    /**
     * @inheritDoc
     */
    public function applyConfig(array $config)
    {
    }



    public function ecrireDebut(?string $table = null)
    {
        var_dump($table);
    }



    public function ecrireFin(?string $table = null)
    {

    }



    public function ecrire(string $table, array $data)
    {
        var_dump($data);
    }

}