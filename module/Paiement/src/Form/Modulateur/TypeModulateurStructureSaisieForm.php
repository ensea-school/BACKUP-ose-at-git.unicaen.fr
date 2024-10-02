<?php

namespace Paiement\Form\Modulateur;

use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Service\TypeModulateurServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of typeModulateurSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeModulateurStructureSaisieForm extends AbstractForm
{
    use StructureServiceAwareTrait;
    use AnneeServiceAwareTrait;


    public function init()
    {
        $hydrator = new TypeModulateurStructureHydrator();
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
            'type'       => Structure::class,
            'options' => [
                'enseignement' => true,
            ],
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





class TypeModulateurStructureHydrator implements HydratorInterface
{
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