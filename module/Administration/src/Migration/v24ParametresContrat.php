<?php

namespace Administration\Migration;

use Administration\Entity\Db\Parametre;
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
        $this->logMsg("valeur de l'ancien avenant".$oldAvenant);

        $this->logMsg("Mise en place des nouveau paramètres de contrat");
        if ($oldAvenant != NULL && $oldAvenant != '') {
            $valeurAvenant = '';
            $valeurMis     = '';
            $valeurEns     = '';
            switch ($oldAvenant) {
                case 'avenant_autorise':
                    $valeurMis     = Parametre::CONTRAT_MIS_MISSION;
                    $valeurAvenant = Parametre::AVENANT_AUTORISE;
                    $valeurEns     = Parametre::CONTRAT_ENS_COMPOSANTE;
                    break;
                case 'avenant_struct':
                    $valeurMis     = Parametre::CONTRAT_MIS_COMPOSANTE;
                    $valeurAvenant = Parametre::AVENANT_AUTORISE;
                    $valeurEns     = Parametre::CONTRAT_ENS_COMPOSANTE;
                    break;
                case 'avenant_desactive':
                    $valeurMis     = Parametre::CONTRAT_MIS_GLOBAL;
                    $valeurAvenant = Parametre::AVENANT_DESACTIVE;
                    $valeurEns     = Parametre::CONTRAT_ENS_GLOBAL;
                    break;
                default:
                    break;
            }

            $this->logMsg("valeur du param avenant ".$valeurAvenant);
            $this->logMsg("valeur du param mission ".$valeurMis);
            $this->logMsg("valeur du param enseignement ".$valeurEns);

            if ($valeurAvenant != '') {
                $data = ['valeur' => $valeurAvenant];
                $this->getBdd()->getTable('PARAMETRE')->update($data, ['nom' => 'avenant']);
            }
            if ($valeurEns != '') {
                $data = ['nom' => 'contrat_ens', 'valeur' => $valeurEns];
                $this->getBdd()->getTable('PARAMETRE')->insert($data);
            }
            if ($valeurMis != '') {
                $data = ['nom' => 'contrat_mis', 'valeur' => $valeurMis];
                $this->getBdd()->getTable('PARAMETRE')->insert($data);
            }
        }
    }
}