<?php
namespace Application\Hydrator\Chargens;

use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\TypeIntervention;
use Application\Provider\Chargens\ChargensProvider;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Entity\Chargens\Noeud;


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
     * @param  array $data
     * @param  Noeud $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $scenarioNoeud = $object->getScenarioNoeud();

        if (array_key_exists('code', $data)) {
            $code = (string)$data['code'];
            if ($object->getCode() != $code) {
                $object->setCode($code);
            }
        }

        if (array_key_exists('libelle', $data)) {
            $libelle = (string)$data['libelle'];
            if ($object->getLibelle() != $libelle) {
                $object->setLibelle($libelle);
            }
        }

        if (array_key_exists('liste', $data)) {
            $liste = (boolean)$data['liste'];
            if ($liste != $object->isListe()) {
                $object->setListe($liste);
            }
        }

        if (array_key_exists('assiduite', $data)) {
            $assiduite = $data['assiduite'] === '' ? null : (float)$data['assiduite'];
            if ($assiduite != $scenarioNoeud->getAssiduite()) {
                $scenarioNoeud->setAssiduite($assiduite);
            }
        }

        if (array_key_exists('hetd', $data)) {
            $hetd = $data['hetd'] === '' ? null : (int)$data['hetd'];
            if ($hetd != $scenarioNoeud->getHetd()) {
                $scenarioNoeud->setHetd($hetd);
            }
        }

        if (array_key_exists('effectifs', $data)) {
            $effectifs = (array)$data['effectifs'];
            foreach ($effectifs as $typeHeuresId => $effectif) {
                $typeHeures = $this->chargens->getEntities()->get(TypeHeures::class, $typeHeuresId);
                $effectif   = $effectif === '' ? null : (int)$effectif;
                if ($effectif !== null || $scenarioNoeud->hasEffectif($typeHeures)) {
                    $scenarioNoeud->getEffectif($typeHeures)->setEffectif($effectif);
                }
            }
        }

        if (array_key_exists('seuils-ouverture', $data)) {
            $seuilsOuverture = (array)$data['seuils-ouverture'];
            foreach ($seuilsOuverture as $typeInterventionId => $seuilOuverture) {
                $typeIntervention = $this->chargens->getEntities()->get(TypeIntervention::class, $typeInterventionId);
                $seuilOuverture   = $seuilOuverture === '' ? null : (int)$seuilOuverture;
                if ($seuilOuverture !== null || $scenarioNoeud->hasSeuil($typeIntervention)) {
                    $scenarioNoeud->getSeuil($typeIntervention)->setOuverture($seuilOuverture);
                }
            }
        }

        if (array_key_exists('seuils-dedoublement', $data)) {
            $seuilsDedoublement = (array)$data['seuils-dedoublement'];
            foreach ($seuilsDedoublement as $typeInterventionId => $seuilDedoublement) {
                $typeIntervention  = $this->chargens->getEntities()->get(TypeIntervention::class, $typeInterventionId);
                $seuilDedoublement = $seuilDedoublement === '' ? null : (int)$seuilDedoublement;
                if ($seuilDedoublement !== null || $scenarioNoeud->hasSeuil($typeIntervention)) {
                    $scenarioNoeud->getSeuil($typeIntervention)->setDedoublement($seuilDedoublement);
                }
            }
        }

        if (array_key_exists('etape', $data)) {
            $etape = (int)$data['etape'];
            $object->setEtape($etape > 0 ? $etape : null);
        }

        if (array_key_exists('element-pedagogique', $data)) {
            $elementPedagogique = (int)$data['element-pedagogique'];
            $object->setElementPedagogique($elementPedagogique > 0 ? $elementPedagogique : null);
        }

        /*if (array_key_exists('types-intervention', $data)) {
            $existingTypesIntervention = $object->getTypeIntervention();
            $typesIntervention = (array)$data['types-intervention'];
            foreach ($typesIntervention as $typeInterventionId) {

            }
        }*/

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  Noeud $object
     *
     * @return array
     */
    public function extract($object)
    {
        $scenarioNoeud = $object->getScenarioNoeud();

        $data = [
            'id'                  => $object->getId(),
            'code'                => $object->getCode(),
            'libelle'             => $object->getLibelle(),
            'liste'               => $object->isListe(),
            'assiduite'           => $scenarioNoeud->getAssiduite(),
            'hetd'                => $scenarioNoeud->getHetd(),
            'effectifs'           => [],
            'seuils-ouverture'    => [],
            'seuils-dedoublement' => [],
            'etape'               => $object->getEtape(false),
            'element-pedagogique' => $object->getElementPedagogique(false),
            'nb-liens-sup'        => $object->getNbLiensSup(),
            'nb-liens-inf'        => $object->getNbLiensInf(),
            'types-intervention'  => array_keys($object->getTypeIntervention()),
        ];

        $effectifs = $scenarioNoeud->getEffectif();
        foreach ($effectifs as $e) {
            $data['effectifs'][$e->getTypeHeures()->getId()] = $e->getEffectif();
        }

        $seuils = $scenarioNoeud->getSeuil();
        foreach ($seuils as $seuil) {
            if ($seuil->getOuverture() !== null) {
                $data['seuils-ouverture'][$seuil->getTypeIntervention()->getId()] = $seuil->getOuverture();
            }
            if ($seuil->getDedoublement() !== null) {
                $data['seuils-dedoublement'][$seuil->getTypeIntervention()->getId()] = $seuil->getDedoublement();
            }
        }

        return $data;
    }

}