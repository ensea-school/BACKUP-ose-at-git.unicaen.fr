<?php

namespace BddAdmin\Data;


class SqlHtmlOutput extends AbstractSqlOutput
{

    /**
     * @inheritDoc
     */
    public function applyConfig(array $config)
    {
    }



    public function ecrireDebut(?string $table = null)
    {
        parent::ecrireDebut($table);
        if ($table) {
            echo '-- Table ' . $table."\n";
        } else {
            echo '<pre>';
        }
    }



    public function ecrireFin(?string $table = null)
    {
        parent::ecrireFin($table);
        if (!$table) {
            echo '</pre>';
        }else{
            echo "\n";
        }
    }



    public function ecrire(string $table, array $data)
    {
        echo parent::ecrire($table, $data);
    }



    public function ecrireLob(string $filename, object $lob)
    {
        /** @var \OCI_Lob $lob */
        echo "-- ECRITURE DU LOB $filename\n";
    }

}