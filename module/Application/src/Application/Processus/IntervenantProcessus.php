<?php

namespace Application\Processus;
use Application\Service\Traits\ContextAwareTrait;
use UnicaenApp\Util;


/**
 * Description of IntervenantProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantProcessus extends AbstractProcessus{
    use ContextAwareTrait;



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
        $orc = '';
        if ($sqlCri != '') {
            $orc[] = '(' . $sqlCri . ')';
        }
        if ($criCode) {
            $orc[] = 'source_code LIKE \'%' . $criCode . '%\'';
        }
        $orc = implode(' OR ', $orc);
        $sql .= ' AND (' . $orc . ') ORDER BY nom_usuel, prenom';

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        $intervenants = [];
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

        return $intervenants;
    }
}