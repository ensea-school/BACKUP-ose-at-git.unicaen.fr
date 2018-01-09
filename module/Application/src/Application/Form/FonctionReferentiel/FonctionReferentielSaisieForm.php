<?php

namespace Application\Form\FonctionReferentiel;

use Application\Form\AbstractForm;
use Application\Service\Traits\DomaineFonctionnelServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\Traits\ContextServiceAwareTrait;

/**
 * Description of FonctionReferentielSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class FonctionReferentielSaisieForm extends AbstractForm
{
    use DomaineFonctionnelServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ContextServiceAwareTrait;

    public function init()
    {
        $hydrator = new FonctionReferentielHydrator();
        $hydrator->setServiceDomaineFonctionnel($this->getServiceDomaineFonctionnel());
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name' => 'code',
            'options' => [
                'label' => "Code",
            ],
            'attributes' => [
                'id' => uniqid('code'),
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'libelle-long',
            'options' => [
                'label' => "Libelle Long",
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'libelle-court',
            'options' => [
                'label' => "Libelle Court",
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'domaine-fonctionnel',
            'options' => [
                'label' => 'Domaine fonctionnel',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
            'type' => 'Select',
        ]);
        $this->add([
            'name' => 'plafond',
            'options' => [
                'label' => "Plafond",
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'structure',
            'options' => [
                'label' => 'Structure',
            ],
            'attributes' => [
                'class' => 'selectpicker',
                'data-live-search' => 'true'
            ],
            'type' => 'Select',
        ]);
        $this->add(new Csrf('security'));
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary'
            ],
        ]);
        // peuplement liste des domaines fonctionnels
        $this->get('domaine-fonctionnel')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceDomaineFonctionnel()->getList()));

        $this->get('structure')
            ->setEmptyOption("(Aucun)")
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getStructures()));
        return $this;
    }



    public function getStructures()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement($serviceStructure->finderByNiveau(2));
        if ($role->getStructure()) {
            $serviceStructure->finderById($role->getStructure()->getId(), $qb); // Filtre
        }

        $structures = $serviceStructure->getList($qb);

        $structures += $serviceStructure->getList( $serviceStructure->finderByNiveau(1) );

        return $structures;
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
            'code' => [
                'required' => false,
            ],
            'libelle-long' => [
                'required' => true,
            ],
            'libelle-court' => [
                'required' => true,
            ],
            'domaine-fonctionnel' => [
                'required' => true,
            ],
            'plafond' => [
                'required' => true,
            ],
            'structure' => [
                'required' => false,
            ],
        ];
    }

}

class FonctionReferentielHydrator implements HydratorInterface
{
    use DomaineFonctionnelServiceAwareTrait;
    use StructureServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\FonctionReferentiel $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelleCourt($data['libelle-court']);
        $object->setLibelleLong($data['libelle-long']);
        if (array_key_exists('domaine-fonctionnel', $data)) {
            $object->setDomaineFonctionnel($this->getServiceDomaineFonctionnel()->get($data['domaine-fonctionnel']));
        }
        $object->setPlafond($data['plafond']);
        if (array_key_exists('structure', $data)) {
            $object->setStructure($this->getServiceStructure()->get($data['structure']));
        }
        return $object;
    }


    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\FonctionReferentiel $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id' => $object->getId(),
            'code' => $object->getCode(),
            'libelle-court' => $object->getLibelleCourt(),
            'libelle-long' => $object->getLibelleLong(),
            'domaine-fonctionnel' => ($s = $object->getDomaineFonctionnel()) ? $s->getId() : null,
            'plafond' => $object->getPlafond(),
            'structure'           => ($s = $object->getStructure()) ? $s->getId() : null,
        ];

        return $data;
    }
}