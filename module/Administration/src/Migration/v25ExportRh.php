<?php

namespace Administration\Migration;

use Intervenant\Entity\Db\TypeIntervenant;
use Unicaen\BddAdmin\Migration\MigrationAction;
use Unicaen\Framework\Application\Application;

class v25ExportRh extends MigrationAction
{


    public function description(): string
    {
        return "Suppression de doublons dans les validations";
    }



    public function utile(): bool
    {

        return $this->manager()->hasNewColumn('STATUT', 'EXPORT_RH');

    }



    public function after(): void
    {
        $bdd            = $this->getBdd();
        $config         = Application::getInstance()->container()->get('config');
        $configExportRh = $config['export-rh'];
        if ($configExportRh['actif']) {
            try {
                $this->logMsg('Activation de l\'export RH sur les statuts de vacataire');
                $sql = "UPDATE statut SET EXPORT_RH = 1 WHERE type_intervenant_id = (SELECT id FROM type_intervenant WHERE code = '" . TypeIntervenant::CODE_EXTERIEUR . "')";
                $bdd->exec($sql);
                $configSiham    = $config['unicaen-siham'];
                $excludeStatuts = $configSiham['exclude-statut-ose'] ?? null;
                if (!empty($excludeStatuts)) {
                    $listeCodeStatut  = implode(',', array_map(fn($k) => "'$k'", array_keys($excludeStatuts)));
                    $listeTitleStatut = implode(',', $excludeStatuts);
                    $this->logMsg("L'export RH a été désactivé pour les statuts suivants : " . $listeTitleStatut);
                    $sql = "UPDATE statut SET EXPORT_RH = 0 WHERE code in ($listeCodeStatut)";
                    $bdd->exec($sql);
                }

            } catch (\Exception $e) {
                $this->logError($e->getMessage());
            }
        }
    }
}
