<?php

namespace Referentiel\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Service\DomaineFonctionnelServiceAwareTrait;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Service\FonctionReferentielServiceAwareTrait;
use UnicaenApp\Util;

/**
 * Description of FonctionReferentielSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class FonctionReferentielSaisieForm extends AbstractForm
{
    use FonctionReferentielServiceAwareTrait;
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
            'type'       => 'Select',
            'name'       => 'parent',
            'options'    => [
                'label'         => 'Type de fonction',
                'empty_option'  => "Pas de type de fonction",
                'value_options' => Util::collectionAsOptions($this->getFonctionsReferentiellesParentes()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'name'       => 'code',
            'options'    => [
                'label' => "Code",
            ],
            'attributes' => [
                'id' => uniqid('code'),
            ],
            'type'       => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle-long',
            'options' => [
                'label' => "Libelle Long",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle-court',
            'options' => [
                'label' => "Libelle Court",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'type'       => 'Select',
            'name'       => 'domaine-fonctionnel',
            'options'    => [
                'label' => 'Domaine fonctionnel',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'type'    => Structure::class,
            'name'    => 'structure',
            'options' => [
                'label' => 'Structure',
            ],
        ]);

        $this->add([
            'name'    => 'etape-requise',
            'options' => [
                'label' => 'Formation à préciser',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'service-statutaire',
            'options' => [
                'label' => 'Les heures peuvent être comptabilisées dans le service statutaire des intervenants',
            ],
            'type'    => 'Checkbox',
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
        // peuplement liste des domaines fonctionnels
        $this->get('domaine-fonctionnel')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceDomaineFonctionnel()->getList()));

        if ($this->getServiceContext()->getStructure()) {
            $this->get('structure')->setEmptyOption(null);
        }

        return $this;
    }



    /**
     * @return FonctionReferentiel[]
     */
    protected function getFonctionsReferentiellesParentes()
    {
        $qb = $this->getServiceFonctionReferentiel()->finderByProperty('parent', null);
        $qb = $this->getServiceFonctionReferentiel()->finderByHistorique($qb);

        $frs = $this->getServiceFonctionReferentiel()->getList($qb);

        return $frs;
    }



    public function getStructures()
    {
        $serviceStructure = $this->getServiceStructure();
        $qb = $serviceStructure->finderByHistorique();
        if ($structure = $this->getServiceContext()->getStructure()) {
            $serviceStructure->finderById($structure->getId(), $qb); // Filtre
        }

        $structures = $serviceStructure->getList($qb);

        return $structures;
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
            'parent'              => [
                'required' => false,
            ],
            'code'                => [
                'required' => false,
            ],
            'libelle-long'        => [
                'required' => true,
            ],
            'libelle-court'       => [
                'required' => true,
            ],
            'domaine-fonctionnel' => [
                'required' => true,
            ],
            'structure'           => [
                'required' => false,
            ],
        ];
    }

}


class FonctionReferentielHydrator implements HydratorInterface
{
    use FonctionReferentielServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use StructureServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param \Referentiel\Entity\Db\FonctionReferentiel $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setParent($this->getServiceFonctionReferentiel()->get(isset($data['parent']) ? $data['parent'] : null));
        $object->setLibelleCourt($data['libelle-court']);
        $object->setLibelleLong($data['libelle-long']);
        if (array_key_exists('domaine-fonctionnel', $data)) {
            $object->setDomaineFonctionnel($this->getServiceDomaineFonctionnel()->get($data['domaine-fonctionnel']));
        }
        if (array_key_exists('structure', $data)) {
            $object->setStructure($this->getServiceStructure()->get($data['structure']));
        }

        $object->setEtapeRequise($data['etape-requise']);
        $object->setServiceStatutaire($data['service-statutaire']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Referentiel\Entity\Db\FonctionReferentiel $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                  => $object->getId(),
            'parent'              => $object->getParent() ? $object->getParent()->getId() : null,
            'code'                => $object->getCode(),
            'libelle-court'       => $object->getLibelleCourt(),
            'libelle-long'        => $object->getLibelleLong(),
            'domaine-fonctionnel' => ($s = $object->getDomaineFonctionnel()) ? $s->getId() : null,
            'structure'           => ($s = $object->getStructure()) ? $s->getId() : null,
            'etape-requise'       => $object->isEtapeRequise(),
            'service-statutaire'  => $object->isServiceStatutaire(),
        ];

        return $data;
    }
}