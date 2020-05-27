<?php
namespace Application\Hydrator;


use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\StatutIntervenant;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\IntervenantDossierServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 *
 *
 */
class IntervenantDossierHydrator implements HydratorInterface
{
    use IntervenantDossierServiceAwareTrait;

    /**
     *
     * @param StatutIntervenant $defaultStatut
     */
    public function __construct(StatutIntervenant $defaultStatut = null)
    {
        $this->setDefaultStatut($defaultStatut);
    }



    /**
     * Extract values from an object
     *
     * @param  IntervenantDossier $object
     *
     * @return array
     */
    public function extract($object)
    {

        $data['DossierIdentite'] = [
            'nomUsuel' => $object->getNomUsuel(),
            'prenom' => $object->getNomUsuel(),
            ''
        ];

        return $data;
    }



    /**
     * @param array  $data
     * @param object $object
     *
     * @return object
     */

    public function hydrate(array $data, $object)
    {
        //$object = new IntervenantDossier();
        $var = "";
        $object->setNomUsuel($data['DossierIdentite']['nomUsuel']);
        $object->setPrenom($data['DossierIdentite']['prenom']);

        return $object;
    }


    private $defaultStatut;

    public function getDefaultStatut()
    {
        return $this->defaultStatut;
    }

    public function setDefaultStatut($defaultStatut = null)
    {
        $this->defaultStatut = $defaultStatut;
        return $this;
    }
}