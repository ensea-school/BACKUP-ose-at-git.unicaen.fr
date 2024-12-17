<?php

namespace Administration\Migration;

use Application\Entity\Db\Parametre;
use Symfony\Component\Filesystem\Filesystem;
use Unicaen\BddAdmin\Migration\MigrationAction;

class v24ParametresContrat extends MigrationAction
{


    public function description(): string
    {
        return "Adapte les paramètres de contrats";
    }



    public function utile(): bool
    {
        $sql   = "select * from parametre where nom = 'contrat_ens' OR nom = 'contrat_mis'";
        $param = $this->getBdd()->select($sql);
        if (!empty($param)) {
            return false;
        } else {
            return true;
        }
    }



    public function before()
    {
        $this->logMsg("Récuperation des anciens paramètres de contrat");
        $sql   = "select VALEUR from parametre where nom = 'avenant'";
        $param = $this->getBdd()->selectOne($sql);
        if (isset($param['VALEUR'])) {
            $oldAvenant = $param['VALEUR'];
        } else {
            exit;
        }


        $this->logMsg("Mise en place des nouveau paramètres de contrat");
        if ($oldAvenant != NULL && $oldAvenant != '') {
            $valeurAvenant = '';
            $valeurMis     = '';
            $valeurEns     = '';
            switch ($oldAvenant) {
                case Parametre::OLD_AVENANT_AUTORISE:
                    $valeurMis     = Parametre::CONTRAT_MIS_MISSION;
                    $valeurAvenant = Parametre::AVENANT_AUTORISE;
                    $valeurEns     = Parametre::CONTRAT_ENS_COMPOSANTE;
                    break;
                case Parametre::OLD_AVENANT_STRUCT:
                    $valeurMis     = Parametre::CONTRAT_MIS_COMPOSANTE;
                    $valeurAvenant = Parametre::AVENANT_AUTORISE;
                    $valeurEns     = Parametre::CONTRAT_ENS_COMPOSANTE;
                    break;
                case Parametre::OLD_AVENANT_DESACTIVE:
                    $valeurMis     = Parametre::CONTRAT_MIS_GLOBALE;
                    $valeurAvenant = Parametre::AVENANT_DESACTIVE;
                    $valeurEns     = Parametre::CONTRAT_ENS_GLOBALE;
                    break;
                default:
                    break;
            }
            if ($valeurAvenant != '') {
                $data = ['valeur' => $valeurAvenant];
                $this->getBdd()->getTable('PARAMETRE')->update($data, ['nom' => 'avenant']);
            }
            if ($valeurMis != '') {
                $data = ['nom' => 'contrat_ens', 'valeur' => $valeurMis];
                $this->getBdd()->getTable('PARAMETRE')->insert($data);
            }
            if ($valeurEns != '') {
                $data = ['nom' => 'contrat_mis', 'valeur' => $valeurEns];
                $this->getBdd()->getTable('PARAMETRE')->insert($data);
            }
        }
    }
}