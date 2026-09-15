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
        $hasServiceStatutaire   = ($fr->getHeuresServiceStatutaire() > 0) || $fr->getHeures('service') > 0;
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
            'solde' => $fr->getSolde(),
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

        if ($hasServiceStatutaire) {
            $this->reconcileServiceWithServiceDu($data['heures']['service'], $data['serviceDu']);
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



    /**
     * Répercute un écart d'arrondi d'un centième sur la plus grande composante
     * du service afin que la somme affichée corresponde au service dû.
     */
    private function reconcileServiceWithServiceDu(array &$service, float $serviceDu): void
    {
        if ($serviceDu <= 0.0 || [] === $service) {
            return;
        }

        $roundedService = array_map(static fn(float $hours): float => round($hours, 2), $service);
        $difference     = round(round($serviceDu, 2) - array_sum($roundedService), 2);

        if (abs($difference) !== 0.01) {
            return;
        }

        $keyToAdjust = array_keys(
            $roundedService,
            max($roundedService),
            true
        )[0];

        $roundedService[$keyToAdjust] = round($roundedService[$keyToAdjust] + $difference, 2);
        $service = $roundedService;
    }



}
