<?php

namespace Formule\Service;


use Formule\Entity\Db\FormuleResultatIntervenant;

/**
 * Description of AfficheurService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AfficheurService
{
    protected bool $distinctionFiFaFc = true;



    public function isDistinctionFiFaFc(): bool
    {
        return $this->distinctionFiFaFc;
    }



    public function setDistinctionFiFaFc(bool $distinctionFiFaFc): AfficheurService
    {
        $this->distinctionFiFaFc = $distinctionFiFaFc;
        return $this;
    }



    public function resultatToJson(FormuleResultatIntervenant $fr): array
    {
        /*$fr = new FormuleResultatIntervenant();
        //$fr->setHeuresServiceStatutaire(192);
        $fr->setHeuresServiceFi(50);
        $fr->setHeuresComplFc(48);
        $fr->setHeuresPrimes(50);*/

        $hasServiceStatutaire   = ($fr->getHeuresServiceStatutaire() > 0) || $fr->getHeures('service') > 0;
        $hasModificationService = abs($fr->getHeuresServiceStatutaire() - $fr->getHeuresServiceModifie()) > 0.01;
        $hasNonPayable          = $fr->getHeures('non-payable') > 0.0;
        $hasPrime               = $fr->getHeuresPrimes() > 0.0;

        $types = [];
        if ($this->isDistinctionFiFaFc()) {
            if ($fr->getHeures(null, 'fi') > 0.0) $types[] = 'fi';
            if ($fr->getHeures(null, 'fa') > 0.0) $types[] = 'fa';
            if ($fr->getHeures(null, 'fc') > 0.0) $types[] = 'fc';
        } else {
            if ($fr->getHeures(null, 'enseignement') > 0) $types[] = 'enseignement';
        }
        if ($fr->getHeures(null, 'referentiel') > 0) {
            $types[] = 'referentiel';
        }


        $data = [
            'serviceStatutaire' => $fr->getHeuresServiceStatutaire(),
            'serviceDu'         => $fr->getServiceDu(),
            'types'              => &$types,
        ];

        if ($hasServiceStatutaire) {
            $data['heures']['service'] = [];
            foreach ($types as $type) {
                $data['heures']['service'][$type] = $fr->getHeures('service', $type);
            }

            $data['heures']['compl'] = [];
            foreach ($types as $type) {
                $data['heures']['compl'][$type] = $fr->getHeures('compl', $type);
            }
        } else {
            $data['heures']['payable'] = [];
            foreach ($types as $type) {
                $data['heures']['payable'][$type] = $fr->getHeures('compl', $type);
            }
        }

        if ($hasPrime) {
            $data['heures']['primes'] = ['total' => $fr->getHeuresPrimes()];
        }

        if ($hasNonPayable) {
            $data['heures']['non-payable'] = [];
            foreach ($types as $type) {
                $data['heures']['non-payable'][$type] = $fr->getHeures('non-payable', $type);
            }
        }

        if (count($types) > 1) {
            $types[] = 'total';
            foreach ($data['heures'] as $categorie => $values) {
                foreach($values as $type => $heures){
                    if(!array_key_exists('total',$data['heures'][$categorie])){
                        $data['heures'][$categorie]['total'] = 0;
                    }
                    if($type != 'total')
                    {
                        $data['heures'][$categorie]['total'] += $heures;
                    }
                }
            }
        }

        if (count($data['heures']) > 1) {
            $data['heures']['total'] = array_fill_keys($types, 0.0);
            foreach ($data['heures'] as $categorie => $ht) {
                if ('primes' !== $categorie && 'total' !== $categorie) {
                    foreach ($types as $type) {
                            $data['heures']['total'][$type] += $ht[$type];
                    }
                }
            }
        }

        return $data;
    }

}