<?php

namespace Formule\Model;

use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleServiceIntervenant;
use Formule\Model\Arrondisseur\Ligne;
use Formule\Model\Arrondisseur\Valeur;
use Formule\Service\FormuleServiceAwareTrait;
use Laminas\Hydrator\ExtractionInterface;

class FormuleDetailsExtractor implements ExtractionInterface
{
    use FormuleServiceAwareTrait;

    private FormuleServiceIntervenant $fres;
    private Formule                   $formule;
    private Ligne                     $trace;

    private array $iParams            = [];
    private array $vhParams           = [];
    private array $typesInterventions = [];
    private array $typesHetd          = [];
    private array $intervenant        = [];
    private array $services           = [];



    public function extract(object $object): array
    {
        if (!$object instanceof FormuleServiceIntervenant) {
            throw new \Exception('L\'objet fourni doit être de type FormuleServiceIntervenant');
        }

        // Initialisation des objets source de données : résultat de formule, formule, trace d'arrondisseur
        $this->fres = $object;

        $this->formule = $this->getServiceFormule()->getCurrent($this->fres->getAnnee());
        if (null == $this->fres->getArrondisseurTrace()) {
            $this->getServiceFormule()->calculer($this->fres);
        }
        $this->trace = $this->fres->getArrondisseurTrace();


        // Préparations
        $this->prepareTypesHetd();
        $this->prepareIntervenant();
        $this->prepareParams();
        $this->prepareServices();


        // Forge du résultat et envoi
        $result = [
            'iParams'            => $this->iParams,
            'vhParams'           => $this->vhParams,
            'typesInterventions' => $this->typesInterventions,
            'typesHetd'          => $this->typesHetd,
            'intervenant'        => $this->intervenant,
            'services'           => $this->services,
        ];

        return $result;


        /* Travail sur les volumes horaires */
        $vhs = $formuleServiceIntervenant->getVolumesHoraires();
        foreach ($vhs as $vh) {
            $serviceId                                                 = $vh->getService() ? 'e' . $vh->getService() : 'r' . $vh->getServiceReferentiel();
            $volumeHoraireId                                           = $vh->getVolumeHoraire() ? 'e' . $vh->getVolumeHoraire() : 'r' . $vh->getVolumeHoraireReferentiel();
            $services[$serviceId]['volumesHoraires'][$volumeHoraireId] = [
                'id' => $vh->getVolumeHoraire() ?? $vh->getVolumeHoraireReferentiel(),

            ];
        }

    }



    private function prepareTypesHetd(): void
    {

        /* Initialisation des types d'heures HETD utiles */
        foreach ($this->trace->getValeurs() as $vn => $valeur) {
            if ($valeur->getValue() != 0.0) {
                $typesHetd[$vn] = true;
            }
            foreach ($this->trace->getSubs() as $sub) {
                foreach ($sub->getValeurs() as $vn => $valeur) {
                    if ($valeur->getValue() != 0.0) {
                        $typesHetd[$vn] = true;
                    }
                }
                foreach ($sub->getSubs() as $ssub) {
                    foreach ($ssub->getValeurs() as $vn => $valeur) {
                        if ($valeur->getValue() != 0.0) {
                            $typesHetd[$vn] = true;
                        }
                    }
                }
            }
        }
        $this->typesHetd = array_keys($typesHetd);
    }



    private function prepareIntervenant(): void
    {
        $this->intervenant = [
            'id'                         => $this->fres->getIntervenantId(),
            'anneeId'                    => $this->fres->getAnnee()->getId(),
            'typeVolumeHoraireId'        => $this->fres->getTypeVolumeHoraire()->getId(),
            'etatVolumeHoraireId'        => $this->fres->getEtatVolumeHoraire()->getId(),
            'typeIntervenant'            => [
                'id'      => $this->fres->getTypeIntervenant()->getId(),
                'code'    => $this->fres->getTypeIntervenant()->getCode(),
                'libelle' => $this->fres->getTypeIntervenant()->getLibelle(),
            ],
            // structure
            'heuresServiceStatutaire'    => $this->fres->getHeuresServiceStatutaire(),
            'heuresServiceModifie'       => $this->fres->getHeuresServiceModifie(),
            'depassementServiceDuSansHC' => $this->fres->isDepassementServiceDuSansHC(),
            'params'                     => [], // peuplé par prepareParams
            'serviceDu'                  => $this->fres->getServiceDu(),
        ];
    }



    private function prepareParams(): void
    {
        // par intervenant
        for ($i = 1; $i <= 5; $i++) {
            if ($plib = $this->formule->{"getIParam$i" . "Libelle"}()) {
                $this->iParams[$i]               = $plib;
                $this->intervenant['params'][$i] = $this->fres->{"getParam$i"}();
            }
        }
        // par volume horaire
        for ($i = 1; $i <= 5; $i++) {
            if ($plib = $this->formule->{"getVhParam$i" . "Libelle"}()) {
                $this->vhParams[$i] = $plib;
            }
        }
    }



    private function prepareServices(): void
    {
        $eIds = [];
        $rIds = [];
        foreach ($this->trace->getSubs() as $sname => $service) {
            if (str_starts_with($sname, 'e')) {
                $eIds[] = (int)substr($sname, 1);
            } elseif (str_starts_with($sname, 'r')) {
                $rIds[] = (int)substr($sname, 1);
            }
            $this->services[$sname] = [
                'type'            => str_starts_with($sname, 'e') ? 'enseignement' : 'referentiel',
                'id'              => substr($sname, 1),
                'libelle'         => $sname,
                'volumesHoraires' => [],
                'heures'          => [],
                'hetd'            => [],
            ];
            foreach ($this->typesHetd as $typeHetd) {
                $valeur                              = $service->getValeur($typeHetd);
                $this->services[$sname]['hetd'][$typeHetd] = $this->valeurToJson($valeur);
            }
        }
        if (!empty($eIds)) {
            $sql   = "
            SELECT
              s.id,
              CASE WHEN ep.id IS NULL THEN
                etab.libelle || CASE WHEN s.description IS NULL THEN '' ELSE ' => ' || s.description END
              ELSE
                '[' || e.code || '] ' || e.libelle || ' => [' || ep.code || '] ' || ep.libelle 
              END libelle
            FROM
              service s
              JOIN etablissement etab ON etab.id = s.etablissement_id
              LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
              LEFT JOIN etape e ON e.id = ep.etape_id
            WHERE
              s.id IN (" . implode(',', $eIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $this->services['e' . $data['ID']]['libelle'] = $data['LIBELLE'];
            }
        }
        if (!empty($rIds)) {
            $sql   = "
            SELECT
              sr.id,
              str.libelle_court || ' - ' || fr.libelle_court || CASE WHEN sr.commentaires IS NULL THEN '' ELSE ' ' || sr.commentaires END libelle
            FROM
              service_referentiel sr
              JOIN structure str ON str.id = sr.structure_id
              JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
            WHERE
              sr.id IN (" . implode(',', $rIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $this->services['r' . $data['ID']]['libelle'] = $data['LIBELLE'];
            }
        }
    }



    private function valeurToJson(Valeur $valeur): array
    {
        return [
            'valeur'  => $valeur->getValueFinale(),
            'arrondi' => $valeur->getArrondi(),
            'original' => $valeur->getValue(),
        ];
    }
}