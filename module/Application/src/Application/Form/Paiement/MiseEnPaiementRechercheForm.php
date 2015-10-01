<?php

namespace Application\Form\Paiement;

use Application\Service\Traits\ContextAwareTrait;
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
    use ServiceLocatorAwareTrait,
        \Application\Service\Traits\TypeIntervenantAwareTrait;
    use ContextAwareTrait;

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

        $this->setAttribute('method', 'post')
             ->setAttribute('class', 'paiement-mise-en-paiement-recherche-form')
             ->setAttribute('id', $this->getId());

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'type-intervenant',
            'options' => [
                'label' => 'Statut des intervenants',
                'value_options' => [
                    '' => "Peu importe",
                    $this->getServiceTypeIntervenant()->getPermanent()->getId() => "Permanent",
                    $this->getServiceTypeIntervenant()->getExterieur()->getId() => "Vacataire"
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
            ],
        ));

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
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'attributes' => [
                'multiple' => 'multiple',
            ],
            'name' => 'intervenants',
            'options' => [
                'label' => 'Intervenants',
            ],
        ]);

        $this->add([
            'name' => 'suite',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Suite...',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->add([
            'name' => 'afficher',
            'type'  => 'Submit',
            'attributes' => [
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->add([
            'name' => 'exporter-pdf',
            'type'  => 'Submit',
            'attributes' => [
                'class' => 'btn btn-default',
            ],
        ]);

        $this->add([
            'name' => 'exporter-csv-etat',
            'type'  => 'Submit',
            'attributes' => [
                'class' => 'btn btn-default',
            ],
        ]);
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
        $annee = $this->getServiceContext()->getAnnee();
        $this->get('periode')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $periodes, false, function($p) use ($annee){ return $p->getLibelleAnnuel($annee); } ) );
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
            'type-intervenant' => [
                'required' => false
            ],
            'structure' => [
                'required' => false
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
    use ServiceLocatorAwareTrait,
        \Application\Service\Traits\IntervenantAwareTrait,
        \Application\Service\Traits\PeriodeAwareTrait,
        \Application\Service\Traits\StructureAwareTrait,
        \Application\Service\Traits\TypeIntervenantAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Paiement\MiseEnPaiementRecherche $object
     * @return \Application\Entity\Paiement\MiseEnPaiementRecherche
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['type-intervenant']) ? (int)$data['type-intervenant'] : null;
        $object->setTypeIntervenant( $this->getServiceTypeIntervenant()->get( $id ) );

        $id = isset($data['structure']) ? (int)$data['structure'] : null;
        $object->setStructure( $this->getServiceStructure()->get( $id ) );

        $id = isset($data['periode']) ? (int)$data['periode'] : null;
        $object->setPeriode( $this->getServicePeriode()->get( $id ) );

        if (isset($data['intervenants']) && is_array($data['intervenants'])){
            foreach( $data['intervenants'] as $id ){
                $object->getIntervenants()->add( $this->getServiceIntervenant()->get($id) );
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
            'type-intervenant'  => $object->getTypeIntervenant()    ? $object->getTypeIntervenant()->getId() : null,
            'structure'         => $object->getStructure()          ? $object->getStructure      ()->getId() : null,
            'periode'           => $object->getPeriode()            ? $object->getPeriode        ()->getId() : null,
            'intervenants'      => [],
        ];
        foreach( $object->getIntervenants() as $intervenant ){
            $data['intervenants'][] = $intervenant->getId();
        }
        return $data;
    }

}