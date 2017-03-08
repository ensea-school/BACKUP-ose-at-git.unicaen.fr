<?php
namespace Application\Hydrator\Chargens;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Entity\Chargens\Lien;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class LienDiagrammeHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Lien  $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $scenarioLien = $object->getScenarioLien();

        if (array_key_exists('noeud-sup', $data)){
            $noeudSup = (int)$data['noeud-sup'];
            if ($object->getNoeudSup(false) != $noeudSup){
                $object->setNoeudSup($noeudSup);
            }
        }

        if (array_key_exists('noeud-inf', $data)){
            $noeudInf = (int)$data['noeud-inf'];
            if ($object->getNoeudInf(false) != $noeudInf){
                $object->setNoeudInf($noeudInf);
            }
        }

        if (array_key_exists('actif', $data)) {
            $actif = $data['actif'] == '1';
            if ($actif != $scenarioLien->isActif()) {
                $scenarioLien->setActif($actif);
            }
        }

        if (array_key_exists('poids', $data)) {
            $poids = (float)$data['poids'];
            if ($poids != $scenarioLien->getPoids()) {
                $scenarioLien->setPoids($poids);
            }
        }

        if (array_key_exists('choix-minimum', $data)) {
            $choixMinimum = $data['choix-minimum'] === '' ? null : (int)$data['choix-minimum'];
            if ($choixMinimum != $scenarioLien->getChoixMinimum()) {
                $scenarioLien->setChoixMinimum($choixMinimum);
            }
        }

        if (array_key_exists('choix-maximum', $data)) {
            $choixMaximum = $data['choix-maximum'] === '' ? null : (int)$data['choix-maximum'];
            if ($choixMaximum != $scenarioLien->getChoixMaximum()) {
                $scenarioLien->setChoixMaximum($choixMaximum);
            }
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  Lien $object
     *
     * @return array
     */
    public function extract($object)
    {
        $scenarioLien = $object->getScenarioLien();

        $data = [
            'id'            => $object->getId(),
            'noeud-sup'     => $object->getNoeudSup(false),
            'noeud-inf'     => $object->getNoeudInf(false),
            'actif'         => $scenarioLien->isActif(),
            'poids'         => $scenarioLien->getPoids(),
            'choix-minimum' => $scenarioLien->getChoixMinimum(),
            'choix-maximum' => $scenarioLien->getChoixMaximum(),
        ];

        return $data;
    }

}