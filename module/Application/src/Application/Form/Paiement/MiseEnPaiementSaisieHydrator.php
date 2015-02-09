<?php
namespace Application\Form\Paiement;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwaretrait;
use Application\Entity\Db\MiseEnPaiement;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementSaisieHydrator implements HydratorInterface, ContextProviderAwareInterface, EntityManagerAwareInterface
{

    use ContextProviderAwareTrait;
    use EntityManagerAwaretrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  MiseEnPaiement $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $em = $this->getEntityManager();

        $centreCout = isset($data['centre-cout']['id']) ? (int)$data['centre-cout']['id'] : null;
        $object->setCentreCout( $centreCout ? $this->getServiceCentreCout()->get($centreCout) : null );

        foreach( MiseEnPaiementSaisieFieldset::getHeures() as $hid => $hdata ){
            $object->{$hdata['method']}( (float)$data[$hid] );
        }

        $formuleResultatService = isset($data['formule-resultat-service']) ? (int)$data['formule-resultat-service'] : null;
        $object->setFormuleResultatService( $formuleResultatService ? $this->getServiceFormuleResultatService()->get($formuleResultatService) : null );

        $formuleResultatServiceReferentiel = isset($data['formule-resultat-service-referentiel']) ? (int)$data['formule-resultat-service-referentiel'] : null;
        $object->setFormuleResultatServiceReferentiel( $formuleResultatServiceReferentiel ? $this->getServiceFormuleResultatServiceReferentiel()->get($formuleResultatServiceReferentiel) : null );

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  MiseEnPaiement $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        if ($object) $data['id'] = $object->getId();
        if ($object->getCentreCout()){
            $data['centre-cout'] = [
                'id'    => $object->getCentreCout()->getId(),
                'label' => (string)$object->getCentreCout()
            ];
        }else{
            $data['centre-cout'] = null;
        }

        foreach( MiseEnPaiementSaisieFieldset::getHeures() as $hid => $hdata ){
            $data[$hid] = $object->{$hdata['method']}();
        }

        $data['formule-resultat-service'] = is_object($object->getFormuleResultatService()) ? $object->getFormuleResultatService()->getId() : null;
        $data['formule-resultat-service-referentiel'] = is_object($object->getFormuleResultatServiceReferentiel()) ? $object->getFormuleResultatServiceReferentiel()->getId() : null;

        return $data;
    }

    /**
     * @return \Application\Service\FormuleResultatService
     */
    protected function getServiceFormuleResultatService()
    {
        return $this->getServiceLocator()->get('applicationFormuleResultatService');
    }

    /**
     * @return \Application\Service\FormuleResultatServiceReferentiel
     */
    protected function getServiceFormuleResultatServiceReferentiel()
    {
        return $this->getServiceLocator()->get('applicationFormuleResultatServiceReferentiel');
    }

    /**
     * @return \Application\Service\CentreCout
     */
    protected function getServiceCentreCout()
    {
        return $this->getServiceLocator()->get('applicationCentreCout');
    }
}