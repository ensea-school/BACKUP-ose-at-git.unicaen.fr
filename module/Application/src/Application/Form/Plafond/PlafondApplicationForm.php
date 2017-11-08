<?php

namespace Application\Form\Plafond;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Plafond;
use Application\Entity\Db\PlafondEtat;
use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeAwareTrait;
use Application\Service\Traits\PlafondEtatServiceAwareTrait;
use Application\Service\Traits\PlafondServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use UnicaenApp\Util;
use Zend\Stdlib\Hydrator\HydratorInterface;



/**
 * Description of PlafondApplicationForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondApplicationForm extends AbstractForm
{
    use StructureAwareTrait;
    use AnneeAwareTrait;
    use PlafondServiceAwareTrait;
    use PlafondEtatServiceAwareTrait;


    public function init()
    {
        $hydrator = new PlafondApplicationFormHydrator;
        $this->setHydrator($hydrator);

        $this->add([
            'name'       => 'plafond',
            'options'    => [
                'label' => 'Plafond',
                'value_options'             => Util::collectionAsOptions($this->getPlafonds()),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'plafondEtat',
            'options'    => [
                'label' => 'État',
                'value_options'             => Util::collectionAsOptions($this->getPlafondsEtats()),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => 'Structure',
                'empty_option'              => "Valable pour tout l'établissement",
                'value_options'             => Util::collectionAsOptions($this->getStructures()),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'anneeDebut',
            'options'    => [
                'label' => 'Année de début',
                'empty_option'              => "Aucune",
                'value_options'             => Util::collectionAsOptions($this->getAnnees()),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'anneeFin',
            'options'    => [
                'label' => 'Année de fin',
                'empty_option'              => "Aucune",
                'value_options'             => Util::collectionAsOptions($this->getAnnees()),
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    /**
     * @return Plafond[]
     */
    protected function getPlafonds()
    {
        return $this->getServicePlafond()->getList();
    }



    /**
     * @return PlafondEtat[]
     */
    protected function getPlafondsEtats()
    {
       return $this->getServicePlafondEtat()->getList();
    }



    /**
     * @return Structure[]
     */
    protected function getStructures()
    {
        $qb = $this->getServiceStructure()->finderByHistorique();
        $this->getServiceStructure()->finderByEnseignement($qb);
        return $this->getServiceStructure()->getList($qb);
    }



    /**
     * @return Annee[]
     */
    protected function getAnnees()
    {
        return $this->getServiceAnnee()->getList();
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
            /* Filtres et validateurs */
        ];
    }

}



class PlafondApplicationFormHydrator implements HydratorInterface
{

    /**
     * @param  array    $data
     * @param           $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* on peuple l'objet à partir du tableau de données */

        return $object;
    }



    /**
     * @param  $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            /* On peuple le tableau avec les données de l'objet */
        ];

        return $data;
    }
}