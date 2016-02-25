<?php

namespace Application\Service;

class PilotageService extends AbstractService
{

    /**
     * @return array
     */
    public function getEcartsEtats()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $sql = "SELECT * FROM V_EXPORT_PILOTAGE_ECARTS_ETATS";

        $filters = [
            'annee_id' => (int)$this->getServiceContext()->getAnnee()->getId(),
        ];

        if ($role && $structure = $role->getStructure()){
            $filters['structure_id'] = $structure->getId();
        }

        $fSql = '';
        foreach($filters as $column => $value){
            if ($fSql != '') $fSql .= ' AND ';
            $fSql .= $column.' = '.$value;
        }
        if ($fSql != ''){
            $sql .= ' WHERE '.$fSql;
        }
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        // récupération des données
        $res = [];
        while ($d = $stmt->fetch()) {
            $res[] = [
                'annee'             => $d['ANNEE'],
                'etat'              => $d['ETAT'],
                'type-heures'       => $d['TYPE_HEURES'],
                'structure'         => $d['STRUCTURE'],
                'intervenant-type'  => $d['INTERVENANT_TYPE'],
                'intervenant-code'  => $d['INTERVENANT_CODE'],
                'intervenant'       => $d['INTERVENANT'],
                'hetd'              => (float)$d['HETD_PAYABLES']
            ];
        }

        return $res;
    }

}