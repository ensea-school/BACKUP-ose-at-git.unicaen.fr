<?php

namespace Chargens\Hydrator;

use Chargens\Entity\Noeud;
use Laminas\Hydrator\HydratorInterface;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class NoeudDbHydrator implements HydratorInterface
{
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
        $id = isset($data['ID']) ? stringToInt($data['ID']) : null;
        $object->setId($id);

        $code = isset($data['CODE']) ? $data['CODE'] : null;
        $object->setCode($code);

        $libelle = isset($data['LIBELLE']) ? $data['LIBELLE'] : null;
        $object->setLibelle($libelle);

        $liste = isset($data['LISTE']) ? stringToBoolean($data['LISTE']) : false;
        $object->setListe($liste);

        $etape = isset($data['ETAPE_ID']) ? stringToInt($data['ETAPE_ID']) : null;
        $object->setEtape($etape);

        $elementPedagogique = isset($data['ELEMENT_PEDAGOGIQUE_ID']) ? stringToInt($data['ELEMENT_PEDAGOGIQUE_ID']) : null;
        $object->setElementPedagogique($elementPedagogique);

        $structure = isset($data['STRUCTURE_ID']) ? stringToInt($data['STRUCTURE_ID']) : null;
        $object->setStructure($structure);

        $nbLiensSup = isset($data['NB_LIENS_SUP']) ? (int)$data['NB_LIENS_SUP'] : 0;
        $object->setNbLiensSup($nbLiensSup);

        $nbLiensInf = isset($data['NB_LIENS_INF']) ? (int)$data['NB_LIENS_INF'] : 0;
        $object->setNbLiensInf($nbLiensInf);

        $typeIntervention = isset($data['TYPE_INTERVENTION_IDS']) ? $data['TYPE_INTERVENTION_IDS'] : [];
        foreach ($typeIntervention as $ti) {
            $object->addTypeIntervention($ti);
        }

        //$this->hydradeSeuilHeures($data, $object);

        return $object;
    }



    public function hydradeSeuilHeures(array $data, $object)
    {
        $seuilsParDefaut = isset($data['SEUILS_PAR_DEFAUT']) ? $data['SEUILS_PAR_DEFAUT'] : [];
        foreach ($seuilsParDefaut as $scenario => $sd) {
            foreach ($sd as $typeIntervention => $seuilParDefaut) {
                $object->setSeuilParDefaut($scenario, $typeIntervention, $seuilParDefaut);
            }
        }

        $heures = isset($data['HEURES']) ? $data['HEURES'] : [];
        foreach ($heures as $scenario => $h) {
            $object->setHeures($scenario, $h);
        }

        $hetd = isset($data['HETD']) ? $data['HETD'] : [];
        foreach ($hetd as $scenario => $h) {
            $object->setHetd($scenario, $h);
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
        $data = [
            'CODE'                   => $object->getCode(),
            'LIBELLE'                => $object->getLibelle(),
            'LISTE'                  => booleanToString($object->isListe(), '1', '0'),
            'ETAPE_ID'               => $object->getEtape(false),
            'ELEMENT_PEDAGOGIQUE_ID' => $object->getElementPedagogique(false),
            'STRUCTURE_ID'           => $object->getStructure(false),
            'TYPE_INTERVENTION_IDS'  => [],
            /* PAS de NB_LIENS_SUP et NB_LIENS_INF qui sont des champs calculés */
        ];

        $typeIntervention = $object->getTypeIntervention();
        foreach ($typeIntervention as $ti) {
            $data['TYPE_INTERVENTION_IDS'][] = $ti->getId();
        }

        return $data;
    }

}