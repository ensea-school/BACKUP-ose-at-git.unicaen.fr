<?php
namespace Application\Form\Intervenant;

use Application\Entity\Db\Dossier as Dossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\StatutIntervenant;
use Application\Service\Traits\DossierServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 *
 *
 */
class DossierHydrator implements HydratorInterface
{
    use DossierServiceAwareTrait;

    /**
     *
     * @param StatutIntervenant $defaultStatut
     */
    public function __construct(StatutIntervenant $defaultStatut = null)
    {
        $this->setDefaultStatut($defaultStatut);
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Intervenant $intervenant
     * @return Intervenant
     */
    public function hydrate(array $data, $intervenant)
    {
        $dossier = $data['dossier']; /* @var $dossier Dossier */

        if (!$dossier->getStatut() && $this->getDefaultStatut()) {
            $dossier->setStatut($this->getDefaultStatut());
        }

        $intervenant
                ->setStatut($dossier->getStatut())
                ->setPremierRecrutement($dossier->getPremierRecrutement());

        return $intervenant;
    }

    /**
     * Extract values from an object
     *
     * @param  Intervenant $intervenant
     * @return array
     */
    public function extract($intervenant)
    {
        return [
            'dossier' => $this->getServiceDossier()->getByIntervenant($intervenant),
        ];
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