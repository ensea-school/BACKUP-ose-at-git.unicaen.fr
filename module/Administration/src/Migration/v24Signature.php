<?php

namespace Administration\Migration;

use Symfony\Component\Filesystem\Filesystem;
use Unicaen\BddAdmin\Migration\MigrationAction;

class v24Signature extends MigrationAction
{

    public function description(): string
    {
        return "Création du répertoire nécessaire pour le fonctionnement de la signature électronique";
    }



    public function utile(): bool
    {
        return $this->manager()->hasColumn('CONTRAT', 'PROCESS_SIGNATURE_ID');
    }



    public function before()
    {
        $filesystem = new Filesystem();

        if (!$filesystem->exists('data/signature')) {
            $this->logMsg("Création du répertoire data/signature");
            $filesystem->mkdir('data/signature');
        }
        $filesystem->chmod('data/signature', 0777);



    }

}