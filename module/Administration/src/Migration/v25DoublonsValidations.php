<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v25DoublonsValidations extends MigrationAction
{


    public function description(): string
    {
        return "Suppression de doublons dans les validations";
    }



    public function utile(): bool
    {
        return null !== $this->getBdd()->selectOne($this->getQuery());
    }



    public function before()
    {
        $stmt = $this->getBdd()->selectEach($this->getQuery());
        while ($d = $stmt->next()) {
            $tableName = $d['TABLE_NAME'];

            if ($tableName == 'VALIDATION_VOL_HORAIRE') {

                $params = [
                    'VOLUME_HORAIRE_ID' => $d['VOLUME_HORAIRE_ID'],
                    'VALIDATION_ID'     => $d['VALIDATION_ID'],
                ];
                $this->getBdd()->getTable($tableName)->delete($params);

            } else {

                $params = [
                    'VOLUME_HORAIRE_REF_ID' => $d['VOLUME_HORAIRE_ID'],
                    'VALIDATION_ID'         => $d['VALIDATION_ID'],
                ];
                $this->getBdd()->getTable($tableName)->delete($params);

            }
        }
    }



    private function getQuery(): string
    {
        return "
        SELECT
          'VALIDATION_VOL_HORAIRE_REF' table_name, volume_horaire_ref_id volume_horaire_id, vid validation_id
        FROM
          (
        SELECT 
          vvh.volume_horaire_ref_id,
          max(v.id) OVER (partition by vvh.volume_horaire_ref_id) vmax,
          v.id vid
        FROM 
          validation_vol_horaire_ref vvh
          JOIN validation v ON v.id = vvh.validation_id
        WHERE
          v.histo_destruction IS NULL
        ) v
        WHERE
          vmax <> vid
          
        UNION ALL
        
        SELECT
          'VALIDATION_VOL_HORAIRE' table_name, volume_horaire_id volume_horaire_id, vid validation_id
        FROM
          (
        SELECT 
          vvh.volume_horaire_id,
          max(v.id) OVER (partition by vvh.volume_horaire_id) vmax,
          v.id vid
        FROM 
          validation_vol_horaire vvh
          JOIN validation v ON v.id = vvh.validation_id
        WHERE
          v.histo_destruction IS NULL
        ) v
        WHERE
          vmax <> vid
        ";
    }
}
