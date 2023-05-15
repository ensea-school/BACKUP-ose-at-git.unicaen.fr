<?php

namespace Paiement\Form\Modulateur;

use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Paiement\Service\TypeModulateurServiceAwareTrait;
use Paiement\Service\TypeModulateurStructureServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of typeModulateurSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class typeModulateurStructureSaisieForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use TypeModulateurServiceAwareTrait;


    public function init()
    {
        $hydrator = new \Application\Form\modulateur\typeModulateurStructureHydrator();
        /**
         * @var $typesModu \Paiement\Entity\Db\typeModulateurStructure[]
         */
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
                'name' => 'type-modulateur',
                'type' => 'Hidden',
            ]
        );

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => 'Structure',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-debut',
            'options'    => [
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getChoixAnnees()),
                'label'         => 'année de début',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-fin',
            'options'    => [
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getChoixAnnees()),
                'label'         => 'année de fin',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);


        $this->add(new Csrf('security'));
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);

        $role             = $this->getServiceContext()->getSelectedIdentityRole();
        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement();
        if ($role && $role->getStructure()) {
            $serviceStructure->finderById($role->getStructure()->getId(), $qb); // Filtre
        }
        $this->get('structure')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceStructure->getList($qb)));

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'annee-debut' => [
                'required' => false,
            ],
            'annee-fin'   => [
                'required' => false,
            ],
        ];
    }

}





class typeModulateurStructureHydrator implements HydratorInterface
{
    use TypeModulateurStructureServiceAwareTrait;
    use StructureServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use TypeModulateurServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                          $data
     * @param \Paiement\Entity\Db\typeModulateurStructure $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setTypeModulateur($this->getServiceTypeModulateur()->getById($data['type-modulateur']));
        if (array_key_exists('structure', $data)) {
            $object->setStructure($this->getServiceStructure()->get($data['structure']));
        }
        if (array_key_exists('annee-debut', $data)) {
            $object->setAnneeDebut($this->getServiceAnnee()->get($data['annee-debut']));
        }
        if (array_key_exists('annee-fin', $data)) {
            $object->setAnneeFin($this->getServiceAnnee()->get($data['annee-fin']));
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Paiement\Entity\Db\typeModulateurStructure $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'              => $object->getId(),
            'type-modulateur' => $object->getTypeModulateur()->getId(),
            'structure'       => ($s = $object->getStructure()) ? $s->getId() : null,
            'annee-debut'     => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
            'annee-fin'       => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
        ];

        return $data;
    }
}