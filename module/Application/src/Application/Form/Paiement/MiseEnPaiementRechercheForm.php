<?php

namespace Application\Form\Paiement;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of MiseEnPaiementRechercheForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementRechercheForm extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     *
     * @var string
     */
    private $id;

    /**
     * Retourne un identifiant unique de formulaire.
     * Une fois ce dernier initialisé, il ne change plus pour l'instance en cours
     *
     * @return string
     */
    public function getId()
    {
        if (null === $this->id) $this->id = uniqid();
        return $this->id;
    }

    /**
     *
     */
    public function init()
    {
        $hydrator = new MiseEnPaiementRechercheFormHydrator;
        $hydrator->setServiceLocator($this->getServiceLocator()->getServiceLocator());

        $this->setHydrator( $hydrator )
             ->setAllowedObjectBindingClass('Application\Entity\Paiement\MiseEnPaiementRecherche');

        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'paiement-mise-en-paiement-recherche-form')
                ->setAttribute('id', $this->getId());

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => "Composante",
            ],
            'attributes' => [
                'class' => 'input-sm',
            ],
            'type' => 'Select',
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'periode',
            'options' => [
                'label' => 'Période',
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'attributes' => [
                'multiple' => 'multiple',
            ],
            'name' => 'intervenants',
            'options' => [
                'label' => 'Intervenants',
            ],
        ]);

        $this->add(array(
            'name' => 'suite',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Suite...',
                'class' => 'btn btn-primary',
            ),
        ));

        $this->add(array(
            'name' => 'afficher',
            'type'  => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-primary',
            ),
        ));

        $this->add(array(
            'name' => 'exporter-pdf',
            'type'  => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-default',
            ),
        ));

        $this->add(array(
            'name' => 'exporter-csv-etat',
            'type'  => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-default',
            ),
        ));

        $this->add(array(
            'name' => 'exporter-csv-winpaie',
            'type'  => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-default',
            ),
        ));
    }

    /**
     *
     * @param array $structures
     */
    public function populateStructures( $structures )
    {
        $this->get('structure')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $structures ) );
    }

    /**
     *
     * @param array $periodes
     */
    public function populatePeriodes( $periodes )
    {
        $this->get('periode')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $periodes ) );
    }

    /**
     *
     * @param array $intervenants
     */
    public function populateIntervenants( $intervenants )
    {
        $this->get('intervenants')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $intervenants ) );
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'structure' => [
                'required' => true
            ],
            'periode' => [
                'required' => false
            ],
            'intervenants' => [
                'required' => false
            ],
        ];
    }
}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementRechercheFormHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Paiement\MiseEnPaiementRecherche $object
     * @return \Application\Entity\Paiement\MiseEnPaiementRecherche
     */
    public function hydrate(array $data, $object)
    {
        $sIntervenant = $this->getServiceLocator()->get('applicationIntervenant');
        /* @var $sIntervenant \Application\Service\Intervenant */

        $sPeriode = $this->getServiceLocator()->get('applicationPeriode');
        /* @var $sPeriode \Application\Service\Periode */

        $sStructure = $this->getServiceLocator()->get('applicationStructure');
        /* @var $sStructure \Application\Service\Structure */

        $id = isset($data['structure']) ? (int)$data['structure'] : null;
        $object->setStructure( $sStructure->get( $id ) );

        $id = isset($data['periode']) ? (int)$data['periode'] : null;
        $object->setPeriode( $sPeriode->get( $id ) );

        if (isset($data['intervenants']) && is_array($data['intervenants'])){
            foreach( $data['intervenants'] as $id ){
                $object->getIntervenants()->add( $sIntervenant->get($id) );
            }
        }
        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Paiement\MiseEnPaiementRecherche $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'structure'     => $object->getStructure()  ? $object->getStructure()->getId()  : null,
            'periode'       => $object->getPeriode()    ? $object->getPeriode()->getId()    : null,
            'intervenants'  => [],
        ];
        foreach( $object->getIntervenants() as $intervenant ){
            $data['intervenants'][] = $intervenant->getId();
        }
        return $data;
    }

}