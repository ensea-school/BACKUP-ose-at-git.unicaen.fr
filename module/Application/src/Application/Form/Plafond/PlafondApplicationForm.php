<?php

namespace Application\Form\Plafond;

use Application\Entity\Db\Annee;
use Application\Entity\Db\PlafondApplication;
use Application\Entity\Db\PlafondEtat;
use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PlafondEtatServiceAwareTrait;
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
    use PlafondEtatServiceAwareTrait;
    use ContextServiceAwareTrait;



    public function init()
    {
        $hydrator = new PlafondApplicationFormHydrator;
        $hydrator->setServiceAnnee($this->getServiceAnnee());
        $hydrator->setServicePlafondEtat($this->getServicePlafondEtat());
        $hydrator->setServiceStructure($this->getServiceStructure());
        $this->setHydrator($hydrator);

        $this->add([
            'name'       => 'plafondEtat',
            'options'    => [
                'label'         => 'État',
                'value_options' => Util::collectionAsOptions($this->getPlafondsEtats()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label'         => 'Structure',
                'empty_option'  => "Valable pour tout l'établissement",
                'value_options' => Util::collectionAsOptions($this->getStructures()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'anneeDebut',
            'options'    => [
                'label'         => 'Année de début',
                'empty_option'  => "Aucune",
                'value_options' => Util::collectionAsOptions($this->getAnnees()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'anneeFin',
            'options'    => [
                'label'         => 'Année de fin',
                'empty_option'  => "Aucune",
                'value_options' => Util::collectionAsOptions($this->getAnnees()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
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
    use StructureAwareTrait;
    use AnneeAwareTrait;
    use PlafondEtatServiceAwareTrait;



    /**
     * @param  array             $data
     * @param PlafondApplication $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $plafondEtat = $this->getServicePlafondEtat()->get($data['plafondEtat']);
        $structure = isset($data['structure']) && $data['structure'] ? $this->getServiceStructure()->get($data['structure']) : null;
        $anneeDebut = isset($data['anneeDebut']) && $data['anneeDebut'] ? $this->getServiceAnnee()->get($data['anneeDebut']) : null;
        $anneeFin = isset($data['anneeFin']) && $data['anneeFin'] ? $this->getServiceAnnee()->get($data['anneeFin']) : null;

        $object->setPlafondEtat($plafondEtat);
        $object->setStructure($structure);
        $object->setAnneeDebut($anneeDebut);
        $object->setAnneeFin($anneeFin);

        return $object;
    }



    /**
     * @param PlafondApplication $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'plafondEtat' => $object->getPlafondEtat()->getId(),
            'structure'   => $object->getStructure() ? $object->getStructure()->getId() : null,
            'anneeDebut'  => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
            'anneeFin'    => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
        ];

        return $data;
    }
}