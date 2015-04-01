<?php
namespace Application\Form\Intervenant;

/**
 *
 *
 */
class DossierHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{
    /**
     *
     * @param \Application\Entity\Db\StatutIntervenant $defaultStatut
     */
    public function __construct(\Application\Entity\Db\StatutIntervenant $defaultStatut)
    {
        $this->setDefaultStatut($defaultStatut);
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return \Application\Entity\Db\IntervenantExterieur
     */
    public function hydrate(array $data, $intervenant)
    {
        $dossier = $data['dossier']; /* @var $dossier \Application\Entity\Db\Dossier */

        if (!$dossier->getStatut()) {
            $dossier->setStatut($this->getDefaultStatut());
        }

        $intervenant
                ->setDossier($dossier)
                ->setStatut($dossier->getStatut())
                ->setPremierRecrutement($dossier->getPremierRecrutement());

        return $intervenant;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return array
     */
    public function extract($intervenant)
    {
        return [
            'dossier' => $intervenant->getDossier(),
        ];
    }

    private $defaultStatut;

    public function getDefaultStatut()
    {
        return $this->defaultStatut;
    }

    public function setDefaultStatut($defaultStatut)
    {
        $this->defaultStatut = $defaultStatut;
        return $this;
    }
}