<?php

namespace Chargens\Hydrator;

use Chargens\Entity\Lien;
use Laminas\Hydrator\HydratorInterface;


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
     * @param array $data
     * @param Lien  $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $scenarioLien = $object->getScenarioLien();

        if ($object->isCanEditActif() && array_key_exists('actif', $data)) {
            $actif = $data['actif'] == '1';
            if ($actif != $scenarioLien->isActif()) {
                $scenarioLien->setActif($actif);
            }
        }

        if ($object->isCanEditPoids() && array_key_exists('poids', $data)) {
            $poids = (float)$data['poids'];
            if ($poids != $scenarioLien->getPoids()) {
                $scenarioLien->setPoids($poids);
            }
        }

        if ($object->isCanEditChoix() && array_key_exists('choix-minimum', $data)) {
            $choixMinimum = stringToInt($data['choix-minimum']);
            if ($choixMinimum !== $scenarioLien->getChoixMinimum()) {
                $scenarioLien->setChoixMinimum($choixMinimum);
            }
        }

        if ($object->isCanEditChoix() && array_key_exists('choix-maximum', $data)) {
            $choixMaximum = stringToInt($data['choix-maximum']);
            if ($choixMaximum !== $scenarioLien->getChoixMaximum()) {
                $scenarioLien->setChoixMaximum($choixMaximum);
            }
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param Lien $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $scenarioLien = $object->getScenarioLien();

        $data = [
            'id'             => $object->getId(),
            'noeud-sup'      => $object->getNoeudSup(false),
            'noeud-inf'      => $object->getNoeudInf(false),
            'actif'          => $scenarioLien->isActif(),
            'poids'          => $scenarioLien->getPoids(),
            'choix-minimum'  => $scenarioLien->getChoixMinimum(),
            'choix-maximum'  => $scenarioLien->getChoixMaximum(),
            'can-edit-actif' => $object->isCanEditActif(),
            'can-edit-poids' => $object->isCanEditPoids(),
            'can-edit-choix' => $object->isCanEditChoix(),
        ];

        return $data;
    }

}