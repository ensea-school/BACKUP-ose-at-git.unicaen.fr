<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v25CentreCoutsTypeMission extends MigrationAction
{


    public function description(): string
    {
        return "Mise en place d'une gestion annualisé des centres de couts de type de mission";
    }



    public function utile(): bool
    {
        $sql = "SELECT
                      cctm.*
                    FROM
                      centre_cout_type_mission cctm
                      JOIN type_mission tm ON tm.id = cctm.type_mission_id
                      JOIN type_mission tma ON tma.code = tm.code AND tma.annee_id > tm.annee_id
                      LEFT JOIN centre_cout_type_mission cctmf ON cctmf.type_mission_id = tma.id AND cctmf.centre_cout_id = cctm.centre_cout_id AND cctmf.structure_id = cctm.structure_id
                    WHERE
                      cctm.histo_destruction IS NULL
                      AND cctmf.id IS NULL";

        $param = $this->getBdd()->select($sql);
        if (empty($param)) {
            return false;
        } else {
            return true;
        }
    }



    public function after()
    {
        $this->logMsg("Mise en place des centres de couts de type de mission sur les années futures");
        $sql = 'SELECT
                  cctm.id as centre_cout_id, tma.annee_id, tma.id as type_mission_id, cctm.structure_id
                FROM
                  centre_cout_type_mission cctm
                  JOIN type_mission tm ON tm.id = cctm.type_mission_id
                  JOIN type_mission tma ON tma.code = tm.code AND tma.annee_id > tm.annee_id
                  LEFT JOIN centre_cout_type_mission cctmf ON cctmf.type_mission_id = tma.id AND cctmf.structure_id = cctm.structure_id
                WHERE
                  cctm.histo_destruction IS NULL
                  AND cctmf.id IS NULL
                  ';

        $selectQuery = $this->getBdd()->selectEach($sql);
        while ($centreCoutTypeMission = $selectQuery->next()) {
            $data = ['CENTRE_COUT_ID' => $centreCoutTypeMission['CENTRE_COUT_ID'], 'TYPE_MISSION_ID' => $centreCoutTypeMission['TYPE_MISSION_ID'], 'STRUCTURE_ID' => $centreCoutTypeMission['STRUCTURE_ID']];
            $this->getBdd()->getTable('CENTRE_COUT_TYPE_MISSION')->insert($data);
        }
    }
}
