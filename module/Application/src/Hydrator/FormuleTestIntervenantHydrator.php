<?php

namespace Application\Hydrator;

use Application\Constants;
use Application\Entity\Db\Annee;
use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Formule;
use Application\Entity\Db\FormuleTestVolumeHoraire;
use Intervenant\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Laminas\Hydrator\HydratorInterface;
use Application\Entity\Db\FormuleTestIntervenant;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleTestIntervenantHydrator implements HydratorInterface
{
    private function findEntity($class, $id)
    {
        if (!$id) return null;

        $em = \Application::$container->get(Constants::BDD);

        return $em->getRepository($class)->find($id);
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                  $data
     * @param FormuleTestIntervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $methods = get_class_methods($object);
        foreach ($methods as $method) {
            if (0 === strpos($method, 'set')) {
                $property = lcfirst(substr($method, 3));
                if (array_key_exists($property, $data)) {
                    switch ($property) {
                        case 'volumeHoraireTest':
                        break;
                        case 'formule':
                            $object->$method($this->findEntity(Formule::class, $data[$property]));
                        break;
                        case 'annee':
                            $object->$method($this->findEntity(Annee::class, $data[$property]));
                        break;
                        case 'typeIntervenant':
                            $object->$method($this->findEntity(TypeIntervenant::class, $data[$property]));
                        break;
                        case 'typeVolumeHoraire':
                            $object->$method($this->findEntity(TypeVolumeHoraire::class, $data[$property]));
                        break;
                        case 'etatVolumeHoraire':
                            $object->$method($this->findEntity(EtatVolumeHoraire::class, $data[$property]));
                        break;
                        default:
                            $object->$method($data[$property]);
                    }
                }
            }
        }

        for ($p = 1; $p < 6; $p++) {
            if (!$object->getFormule()->{'getIParam' . $p . 'Libelle'}()) {
                $object->{'setParam' . $p}(null);
            }
        }

        if (isset($data['volumeHoraireTest'])) {
            $vhs = $object->getVolumeHoraireTest()->toArray();
            foreach ($data['volumeHoraireTest'] as $index => $vhta) {
                $exists   = isset($vhs[$index]);
                $toDelete = $vhta['structureCode'] == null;

                if ($exists && $toDelete) {
                    $object->removeVolumeHoraireTest($vhs[$index]);
                } elseif (!$exists && !$toDelete) {
                    $vhs[$index] = new FormuleTestVolumeHoraire();
                    $vhs[$index]->setIntervenantTest($object);
                    $object->addVolumeHoraireTest($vhs[$index]);
                }
                if (!$toDelete) {
                    $this->hydrateVolumeHoraire($vhta, $vhs[$index]);
                }
            }
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param FormuleTestIntervenant $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data    = [
        ];
        $methods = get_class_methods($object);
        foreach ($methods as $method) {
            if (0 === strpos($method, 'get')) {
                $property = lcfirst(substr($method, 3));
                switch ($property) {
                    case 'volumeHoraireTest':
                    break;
                    case 'formule':
                    case 'annee':
                    case 'typeIntervenant':
                    case 'typeVolumeHoraire':
                    case 'etatVolumeHoraire':
                        $dep             = $object->$method();
                        $data[$property] = $dep ? $dep->getId() : null;
                    break;
                    default:
                        $data[$property] = $object->$method();
                }
            }
        }

        $vhts = [];
        foreach ($object->getVolumeHoraireTest() as $key => $vht) {
            $vhts[$key] = $this->extractVolumeHoraire($vht);
        }

        $data['volumeHoraireTest'] = $vhts;

        return $data;
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                    $data
     * @param FormuleTestVolumeHoraire $object
     *
     * @return object
     */
    public function hydrateVolumeHoraire(array $data, $object)
    {
        $methods = get_class_methods($object);
        foreach ($methods as $method) {
            if (0 === strpos($method, 'set')) {
                $property = lcfirst(substr($method, 3));
                if (array_key_exists($property, $data)) {
                    switch ($property) {
                        case 'heures':
                            $object->setHeures($data[$property] == null ? 0 : $data[$property]);
                        break;
                        default:
                            $object->$method($data[$property]);
                    }
                }
            }
        }

        if (array_key_exists('typeInterventionCode', $data)) {
            $ti = $data['typeInterventionCode'];
            if ('REFERENTIEL' == $ti) {
                $object->setReferentiel(true);
                $object->setTypeInterventionCode(null);
            } else {
                $object->setReferentiel(false);
            }
        }

        for ($p = 1; $p < 6; $p++) {
            if (!$object->getIntervenantTest()->getFormule()->{'getVhParam' . $p . 'Libelle'}()) {
                $object->{'setParam' . $p}(null);
            }
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param FormuleTestVolumeHoraire $object
     *
     * @return array
     */
    private function extractVolumeHoraire($object)
    {
        $data    = [];
        $methods = get_class_methods($object);
        foreach ($methods as $method) {
            if (0 === strpos($method, 'get')) {
                $property = lcfirst(substr($method, 3));
                switch ($property) {
                    case 'intervenantTest':
                    break;
                    default:
                        $data[$property] = $object->$method();
                }
            }
        }

        if ($object->getReferentiel()) {
            $data['typeInterventionCode'] = 'REFERENTIEL';
        }

        return $data;
    }
}


