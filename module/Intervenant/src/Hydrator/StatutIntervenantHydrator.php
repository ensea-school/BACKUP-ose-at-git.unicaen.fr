<?php

namespace Intervenant\Hydrator;


use Application\Entity\Db\TypeAgrementStatut;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\TypeAgrementServiceAwareTrait;
use Application\Service\Traits\TypeAgrementStatutServiceAwareTrait;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

/**
 *
 *
 */
class StatutIntervenantHydrator implements HydratorInterface
{

    use TypeIntervenantServiceAwareTrait;
    use TypeAgrementServiceAwareTrait;
    use TypeAgrementStatutServiceAwareTrait;
    use DossierAutreServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                         $data
     * @param \Intervenant\Entity\Db\Statut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Intervenant\Entity\Db\Statut $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [

        ];

        /*Gestion des champs autres*/
        $champsAutres = $object->getChampsAutres();
        if (!empty($champsAutres)) {
            foreach ($champsAutres as $champ) {
                $key        = 'champ-autre-' . $champ->getId();
                $data[$key] = 1;
            }
        }

        $typesAgrementsStatuts = $object->getTypeAgrementStatut();
        foreach ($typesAgrementsStatuts as $tas) {
            if (!$tas->getHistoDestruction()) {
                $data[$tas->getType()->getCode()]                = 1;
                $data[$tas->getType()->getCode() . '-DUREE_VIE'] = $tas->getDureeVie();
            }
        }


        return $data;
    }
}