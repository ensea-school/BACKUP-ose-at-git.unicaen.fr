<?php

namespace OffreFormation\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Service\Traits\DomaineFonctionnelServiceAwareTrait;
use OffreFormation\Service\Traits\TypeFormationServiceAwareTrait;

/**
 * Description of EtapeSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisie extends AbstractForm
{
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use TypeFormationServiceAwareTrait;

    private $typesFormation;



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        /* Définition de l'hydrateur */
        $hydrator = new EtapeSaisieHydrator;
        $hydrator->setServiceDomaineFonctionnel($this->getServiceDomaineFonctionnel());
        $hydrator->setServiceStructure($this->getServiceStructure());
        $hydrator->setServiceTypeFormation($this->getServiceTypeFormation());
        $this->setHydrator($hydrator);
        $this->setAttribute('class', 'etape-saisie');
        $this->setAttribute('action', $this->getCurrentUrl());

        /* construction du formulaire */
        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => 'Code',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => 'Libellé',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'       => 'type-formation',
            'options'    => [
                'label' => 'Type de formation',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'    => 'niveau',
            'options' => [
                'label' => 'Niveau',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'specifique-echanges',
            'options' => [
                'label' => 'Spécifique aux échanges',
            ],
            'type'    => 'Checkbox',
        ]);

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
            'name'       => 'domaine-fonctionnel',
            'options'    => [
                'label' => 'Domaine fonctionnel',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement();
        if ($structure = ($role ? $role->getStructure() : null)) {
            $serviceStructure->finderById($role->getStructure()->getId(), $qb); // Filtre
        }
        $this->get('structure')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceStructure->getList($qb)));

        // peuplement liste des types de formation
        $valueOptions = \UnicaenApp\Util::collectionAsOptions($this->getTypesFormation());
        $this->get('type-formation')
            ->setEmptyOption(count($valueOptions) > 1 ? "(Sélectionnez un type...)" : null)
            ->setValueOptions($valueOptions);

        // peuplement liste des domaines fonctionnels
        $this->get('domaine-fonctionnel')
            ->setEmptyOption("(Aucun)")
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceDomaineFonctionnel()->getList()));

        $localContext = $this->getServiceLocalContext();

        if ($structure = $localContext->getStructure()) {
            // si un filtre structure est positionné dans le contexte local, on l'utilise
            $this->get('structure')->setValue($structure->getId());
        }

        // init niveau
        if ($localContext->getNiveau()) {
            // si un filtre niveau est positionné dans le contexte local, on l'utilise
            $this->get('niveau')->setValue($localContext->getNiveau()->getNiv());
        }
    }



    /**
     * @return \OffreFormation\Entity\Db\TypeFormation[]
     */
    private function getTypesFormation()
    {
        if (null === $this->typesFormation) {
            $qb = null;
            if (($niveau = $this->getServiceLocalContext()->getNiveau())) {
                $qb = $this->getServiceTypeFormation()->finderByNiveau($niveau);
            }
            $this->typesFormation = $this->getServiceTypeFormation()->getList($qb);
        }

        return $this->typesFormation;
    }



    /**
     * Retourne pour chaque type de formation le flag indiquant si la saisie d'un niveau est pertienent ou non.
     *
     * @return array id => bool
     */
    public function getPertinencesNiveau()
    {
        $pertinencesNiveau = [];
        foreach ($this->getTypesFormation() as $tf) {
            /* @var $tf \OffreFormation\Entity\Db\TypeFormation */
            $pertinencesNiveau[$tf->getId()] = (bool)$tf->getGroupe()->getPertinenceNiveau();
        }

        return $pertinencesNiveau;
    }



    /**
     *
     * @return bool
     */
    private function getRequiredNiveau()
    {
        $typeFormation     = $this->get('type-formation')->getValue();
        $pertinencesNiveau = $this->getPertinencesNiveau();
        $pertinent         = isset($pertinencesNiveau[$typeFormation]) && (bool)$pertinencesNiveau[$typeFormation];

        return $pertinent;
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
            'code'                => [
                'required' => true,
            ],
            'libelle'             => [
                'required' => true,
            ],
            'type-formation'      => [
                'required' => true,
            ],
            'niveau'              => [
                'required'   => $this->getRequiredNiveau(),
                'validators' => [
                    ['name' => 'Int'],
                ],
            ],
            'structure'           => [
                'required' => false,
            ],
            'domaine-fonctionnel' => [
                'required' => false,
            ],
        ];
    }
}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisieHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use TypeFormationServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                               $data
     * @param \OffreFormation\Entity\Db\Etape $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setSourceCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setTypeFormation($this->getServiceTypeFormation()->get($data['type-formation']));
        if (array_key_exists('niveau', $data)) {
            $object->setNiveau($data['niveau']);
        }
        $object->setSpecifiqueEchanges($data['specifique-echanges']);
        if (array_key_exists('structure', $data)) {
            $object->setStructure($this->getServiceStructure()->get($data['structure']));
        }
        if (array_key_exists('domaine-fonctionnel', $data)) {
            $object->setDomaineFonctionnel($this->getServiceDomaineFonctionnel()->get($data['domaine-fonctionnel']));
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \OffreFormation\Entity\Db\Etape $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'code'                => $object->getCode(),
            'libelle'             => $object->getLibelle(),
            'id'                  => $object->getId(),
            'type-formation'      => ($tf = $object->getTypeFormation()) ? $tf->getId() : null,
            'niveau'              => $object->getNiveau(),
            'specifique-echanges' => $object->getSpecifiqueEchanges(),
            'structure'           => ($s = $object->getStructure()) ? $s->getId() : null,
            'domaine-fonctionnel' => ($s = $object->getDomaineFonctionnel()) ? $s->getId() : null,
        ];

        return $data;
    }
}