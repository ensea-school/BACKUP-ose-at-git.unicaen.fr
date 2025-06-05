<?php

namespace Chargens\Hydrator;

use Chargens\Entity\Noeud;
use Chargens\Provider\ChargensProvider;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Entity\Db\TypeIntervention;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class NoeudDiagrammeHydrator implements HydratorInterface
{
    /**
     * @var ChargensProvider
     */
    private $chargens;



    /**
     * NoeudProvider constructor.
     *
     * @param ChargensProvider $chargens
     */
    public function __construct(ChargensProvider $chargens)
    {
        $this->chargens = $chargens;
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param Noeud $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $scenarioNoeud = $object->getScenarioNoeud();

        if ($object->isCanEditAssiduite() && array_key_exists('assiduite', $data)) {
            $assiduite = stringToFloat($data['assiduite']);
            if ($assiduite != $scenarioNoeud->getAssiduite()) {
                $scenarioNoeud->setAssiduite($assiduite);
            }
        }

        if ($object->isCanEditEffectifs() && array_key_exists('effectifs', $data)) {
            $effectifs = (array)$data['effectifs'];
            foreach ($effectifs as $typeHeuresId => $effectif) {
                $typeHeures = $this->chargens->getEntities()->get(TypeHeures::class, $typeHeuresId);
                $etape      = $object->getEtape(true);
                if ($etape) { // on re rafraichit que pour l'étape
                    $effectif = stringToInt($effectif);
                    if ($effectif !== null || $scenarioNoeud->hasEffectif($typeHeures, $etape)) {
                        $scenarioNoeud->getEffectif($typeHeures, $etape)->setEffectif($effectif);
                    }
                }
            }
        }

        if ($object->isCanEditSeuils() && array_key_exists('seuils-ouverture', $data)) {
            $seuilsOuverture = (array)$data['seuils-ouverture'];
            foreach ($seuilsOuverture as $typeInterventionId => $seuilOuverture) {
                $typeIntervention = $this->chargens->getEntities()->get(TypeIntervention::class, $typeInterventionId);
                $seuilOuverture   = stringToInt($seuilOuverture);
                if ($seuilOuverture !== null || $scenarioNoeud->hasSeuil($typeIntervention)) {
                    $scenarioNoeud->getSeuil($typeIntervention)->setOuverture($seuilOuverture);
                }
            }
        }

        if ($object->isCanEditSeuils() && array_key_exists('seuils-dedoublement', $data)) {
            $seuilsDedoublement = (array)$data['seuils-dedoublement'];
            foreach ($seuilsDedoublement as $typeInterventionId => $seuilDedoublement) {
                $typeIntervention  = $this->chargens->getEntities()->get(TypeIntervention::class, $typeInterventionId);
                $seuilDedoublement = stringToInt($seuilDedoublement);
                if ($seuilDedoublement !== null || $scenarioNoeud->hasSeuil($typeIntervention)) {
                    $scenarioNoeud->getSeuil($typeIntervention)->setDedoublement($seuilDedoublement);
                }
            }
        }

        if ($object->isCanEditAssiduite() && array_key_exists('seuils-assiduite', $data)) {
            $seuilsAssiduite = (array)$data['seuils-assiduite'];
            foreach ($seuilsAssiduite as $typeInterventionId => $seuilAssiduite) {
                $typeIntervention = $this->chargens->getEntities()->get(TypeIntervention::class, $typeInterventionId);
                $seuilAssiduite   = stringToFloat($seuilAssiduite);
                if ($seuilAssiduite !== null || $scenarioNoeud->hasSeuil($typeIntervention)) {
                    $scenarioNoeud->getSeuil($typeIntervention)->setAssiduite($seuilAssiduite);
                }
            }
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param Noeud $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $scenarioNoeud = $object->getScenarioNoeud();

        $data = [
            'id'                         => $object->getId(),
            'code'                       => $object->getCode(),
            'libelle'                    => $object->getLibelle(),
            'liste'                      => $object->isListe(),
            'assiduite'                  => $scenarioNoeud->getAssiduite(),
            'heures'                     => $scenarioNoeud->getHeures(),
            'hetd'                       => $scenarioNoeud->getHetd(),
            'effectifs'                  => [],
            'seuils-ouverture'           => [],
            'seuils-dedoublement'        => [],
            'seuils-dedoublement-defaut' => [],
            'seuils-assiduite'           => [],
            'etape'                      => $object->getEtape(false),
            'element-pedagogique'        => $object->getElementPedagogique(false),
            'nb-liens-sup'               => $object->getNbLiensSup(),
            'nb-liens-inf'               => $object->getNbLiensInf(),
            'types-intervention'         => array_keys($object->getTypeIntervention()),
            'can-edit-assiduite'         => $object->isCanEditAssiduite(),
            'can-edit-seuils'            => $object->isCanEditSeuils(),
            'can-edit-effectifs'         => $object->isCanEditEffectifs(),
        ];

        $effectifs = $scenarioNoeud->getEffectif();
        foreach ($effectifs as $effs) {
            foreach ($effs as $e) {
                if (!isset($data['effectifs'][$e->getTypeHeures()->getId()])) {
                    $data['effectifs'][$e->getTypeHeures()->getId()] = 0;
                }
                $data['effectifs'][$e->getTypeHeures()->getId()] += $e->getEffectif();
            }
        }

        $seuils = $scenarioNoeud->getSeuil();
        foreach ($seuils as $seuil) {
            if ($seuil->getOuverture() !== null) {
                $data['seuils-ouverture'][$seuil->getTypeIntervention()->getId()] = $seuil->getOuverture();
            }
            if ($seuil->getDedoublement() !== null) {
                $data['seuils-dedoublement'][$seuil->getTypeIntervention()->getId()] = $seuil->getDedoublement();
            }
            if ($seuil->getAssiduite() !== null) {
                $data['seuils-assiduite'][$seuil->getTypeIntervention()->getId()] = $seuil->getAssiduite();
            }
        }

        $seuilsDefaut = $scenarioNoeud->getSeuilParDefaut();
        foreach ($seuilsDefaut as $typeIntervention => $dedoublement) {
            $data['seuils-dedoublement-defaut'][$typeIntervention] = $dedoublement;
        }

        return $data;
    }

}