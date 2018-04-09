<?php

namespace Application\Processus;

use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TblService;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\IntervenantSuppressionData;
use Application\Processus\Intervenant\SuppressionDataProcessus;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of IntervenantProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;



    /**
     * @param string  $critere
     * @param integer $limit
     *
     * @return array
     */
    public function rechercher($critere, $limit = 50)
    {
        if (strlen($critere) < 2) return [];

        $anneeId = (int)$this->getServiceContext()->getAnnee()->getId();

        $critere  = Util::reduce($critere);
        $criteres = explode('_', $critere);

        $sql     = 'SELECT * FROM V_INTERVENANT_RECHERCHE WHERE rownum <= ' . (int)$limit . ' AND annee_id = ' . $anneeId;
        $sqlCri  = '';
        $criCode = 0;

        foreach ($criteres as $c) {
            $cc = (int)$c;
            if (0 === $cc) {
                if ($sqlCri != '') $sqlCri .= ' AND ';
                $sqlCri .= 'critere LIKE q\'[%' . $c . '%]\'';
            } else {
                $criCode = $cc;
            }
        }
        $orc = [];
        if ($sqlCri != '') {
            $orc[] = '(' . $sqlCri . ')';
        }
        if ($criCode) {
            $orc[] = 'source_code LIKE \'%' . $criCode . '%\'';
        }
        $orc = implode(' OR ', $orc);
        $sql .= ' AND (' . $orc . ') ORDER BY nom_usuel, prenom';

        $intervenants = [];

        try {
            $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
            while ($r = $stmt->fetch()) {
                $intervenants[$r['SOURCE_CODE']] = [
                    'civilite'         => $r['CIVILITE'],
                    'nom'              => $r['NOM_USUEL'],
                    'prenom'           => $r['PRENOM'],
                    'date-naissance'   => new \DateTime($r['DATE_NAISSANCE']),
                    'structure'        => $r['STRUCTURE'],
                    'numero-personnel' => $r['SOURCE_CODE'],
                ];
            }
        }catch(\Exception $e){
            // à traiter si la vue source intervenant n'existe pas!!
        }

        return $intervenants;
    }



    /**
     * @param Intervenant       $intervenant
     * @param TypeVolumeHoraire $typeVolumehoraire
     * @param EtatVolumeHoraire $etatVolumeHoraire
     *
     * @return boolean
     */
    public function hasHeuresEnseignement(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        $heuresCol = $etatVolumeHoraire->isValide() ? 'valide' : 'nbvh';

        $dql = "
        SELECT
          s        
        FROM
          " . TblService::class . " s
        WHERE
            s.intervenant = :intervenant
            AND s.typeVolumeHoraire = :typeVolumeHoraire
            AND s.$heuresCol > 0
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setMaxResults(1);
        $query->setParameters(compact('intervenant', 'typeVolumeHoraire'));

        $s = $query->getResult();

        return count($s) > 0;
    }



    public function getSuppressionData(Intervenant $intervenant)
    {
        return SuppressionDataProcessus::run($intervenant);
    }



    public function deleteRecursive(IntervenantSuppressionData $isd, array $ids)
    {
        return SuppressionDataProcessus::delete($isd, $ids);
    }
}