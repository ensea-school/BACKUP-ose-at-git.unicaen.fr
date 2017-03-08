<?php
namespace Application\Hydrator\Chargens;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Entity\Chargens\Noeud;


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
     * @param  array $data
     * @param  Noeud $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['ID']) ? (int)$data['ID'] : 0;
        $object->setId($id == 0 ? null : $id);

        $code = isset($data['CODE']) ? $data['CODE'] : null;
        $object->setCode($code);

        $libelle = isset($data['LIBELLE']) ? $data['LIBELLE'] : null;
        $object->setLibelle($libelle);

        $liste = isset($data['LISTE']) ? $data['LISTE'] : '0';
        $object->setListe($liste == '1');

        $etape = isset($data['ETAPE_ID']) ? (int)$data['ETAPE_ID'] : 0;
        $object->setEtape($etape == 0 ? null : $etape);

        $elementPedagogique = isset($data['ELEMENT_PEDAGOGIQUE_ID']) ? (int)$data['ELEMENT_PEDAGOGIQUE_ID'] : 0;
        $object->setElementPedagogique($elementPedagogique == 0 ? null : $elementPedagogique);

        $nbLiensSup = isset($data['NB_LIENS_SUP']) ? (int)$data['NB_LIENS_SUP'] : 0;
        $object->setNbLiensSup($nbLiensSup);

        $nbLiensInf = isset($data['NB_LIENS_INF']) ? (int)$data['NB_LIENS_INF'] : 0;
        $object->setNbLiensInf($nbLiensInf);

        $typeIntervention = isset($data['TYPE_INTERVENTION_IDS']) ? $data['TYPE_INTERVENTION_IDS'] : [];
        foreach ($typeIntervention as $ti) {
            $object->addTypeIntervention($ti);
        }

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
        $data = [
            'CODE'                   => $object->getCode(),
            'LIBELLE'                => $object->getLibelle(),
            'LISTE'                  => $object->isListe() ? '1' : '0',
            'ETAPE_ID'               => $object->getEtape(false),
            'ELEMENT_PEDAGOGIQUE_ID' => $object->getElementPedagogique(false),
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