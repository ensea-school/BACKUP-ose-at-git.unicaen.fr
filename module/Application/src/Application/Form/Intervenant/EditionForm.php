<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Civilite;
use Application\Entity\Db\Discipline;
use Application\Entity\Db\Grade;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Structure;
use Application\Filter\FloatFromString;
use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DisciplineServiceAwareTrait;
use Application\Service\Traits\GradeServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenImport\Entity\Db\Source;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;
use Zend\Form\Element;
use Zend\Form\FormInterface;

/**
 * Description of EditionForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EditionForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use SchemaServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ContextServiceAwareTrait;
    use GradeServiceAwareTrait;
    use DisciplineServiceAwareTrait;
    use SourceServiceAwareTrait;

    protected $hydratorElements = [
        'id'                 => ['type' => 'int'],
        'civilite'           => ['type' => Civilite::class],
        'nomUsuel'           => ['type' => 'string'],
        'nomPatronymique'    => ['type' => 'string'],
        'prenom'             => ['type' => 'string'],
        'dateNaissance'      => ['type' => \DateTime::class],
        'statut'             => ['type' => StatutIntervenant::class],
        'structure'          => ['type' => Structure::class],
        'discipline'         => ['type' => Discipline::class],
        'grade'              => ['type' => Grade::class],
        'code'               => ['type' => 'string'],
        'utilisateurCode'    => ['type' => 'string'],
        'source'             => ['type' => Source::class],
        'sourceCode'         => ['type' => 'string'],
        'syncStatut'         => ['type' => 'bool'],
        'syncStructure'      => ['type' => 'bool'],
        'montantIndemniteFc' => ['type' => 'float'],
    ];

    protected $readOnly         = false;



    public function init()
    {
        $hydrator = new GenericHydrator($this->getServiceSource()->getEntityManager(), $this->hydratorElements);
        $this->setHydrator($hydrator);
        $this->setAttribute('action', $this->getCurrentUrl());
        $this->setAttribute('class', 'form-intervenant-edition no-intranavigation');


        $this->add([
            'name'    => 'civilite',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Civilité',
                'empty_option'  => '- Non renseignée -',
                'value_options' => Util::collectionAsOptions($this->getServiceCivilite()->getList()),
            ],
        ]);

        $this->add([
            'name'    => 'nomUsuel',
            'options' => [
                'label'         => 'Nom usuel <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'nomPatronymique',
            'type'    => 'Text',
            'options' => [
                'label' => 'Nom de naissance',
            ],

        ]);

        $this->add([
            'name'    => 'prenom',
            'type'    => 'Text',
            'options' => [
                'label'         => 'Prénom <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],

        ]);

        $this->add([
            'name'       => 'dateNaissance',
            'type'       => 'DateTime',
            'options'    => [
                'label'         => 'Date de naissance <span class="text-danger">*</span>',
                'format'        => Util::DATE_FORMAT,
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'placeholder' => "jj/mm/aaaa",
            ],
        ]);

        $this->add([
            'name'       => 'statut',
            'type'       => 'Select',
            'options'    => [
                'label'         => 'Statut',
                'value_options' => $this->getStatuts(),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'structure',
            'type'       => 'Select',
            'options'    => [
                'label'         => 'Structure',
                'empty_option'  => '- Non renseignée -',
                'value_options' => Util::collectionAsOptions($this->getStructures()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'discipline',
            'type'       => 'Select',
            'options'    => [
                'label'         => 'Discipline',
                'empty_option'  => '- Non renseignée -',
                'value_options' => Util::collectionAsOptions($this->getDisciplines()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'grade',
            'type'       => 'Select',
            'options'    => [
                'label'         => 'Grade',
                'empty_option'  => '- Non renseigné -',
                'value_options' => Util::collectionAsOptions($this->getGrades()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'montantIndemniteFc',
            'options'    => [
                'label'  => "Montant annuel de la rémunération FC D714-60 :",
                'suffix' => '€',
            ],
            'attributes' => [
                'value' => '0',
                'title' => "Nombre d'heures",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'    => 'code',
            'type'    => 'Text',
            'options' => [
                'label'         => 'Code <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],
        ]);

        $this->add([
            'name'    => 'utilisateurCode',
            'type'    => 'Text',
            'options' => [
                'label' => 'Identifiant LDAP éventuel (' . \AppConfig::get('ldap', 'utilisateurCode', 'supannEmpId') . ')',
            ],
        ]);

        $this->add([
            'name'    => 'source',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Source des données',
                'value_options' => Util::collectionAsOptions($this->getServiceSource()->getList()),
            ],
        ]);

        $this->add([
            'name'    => 'sourceCode',
            'type'    => 'Text',
            'options' => [
                'label' => 'Code source',
            ],
        ]);

        $this->add([
            'name'       => 'syncStatut',
            'options'    => [
                'label' => 'Synchronisation du statut',
            ],
            'attributes' => [
                'title' => 'Si pas coché, alors le statut ne sera plus défini par le connecteur ni par le formulaire de saisie des données personnelles, '
                    . 'mais uniquement par la valeur renseignée ci-dessus',
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'syncStructure',
            'options'    => [
                'label' => 'Synchronisation de la structure',
            ],
            'attributes' => [
                'title' => 'Si pas coché, alors la structure ne sera plus définie par le connecteur, mais uniquement par la valeur renseignée ci-dessus',
            ],
            'type'       => 'Checkbox',
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
    }



    public function getStructures(): array
    {
        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement();
        $serviceStructure->finderByHistorique($qb);

        return $serviceStructure->getList($qb);
    }



    public function getDisciplines(): array
    {
        return $this->getServiceDiscipline()->getList($this->getServiceDiscipline()->finderByHistorique());
    }



    public function getGrades(): array
    {
        return $this->getServiceGrade()->getList($this->getServiceGrade()->finderByHistorique());
    }



    public function getStatuts(): array
    {
        $statuts = $this->getServiceStatutIntervenant()->getList($this->getServiceStatutIntervenant()->finderByHistorique());
        $res     = [];
        foreach ($statuts as $statut) {
            $ti = $statut->getTypeIntervenant();
            if (!isset($res[$ti->getId()])) {
                $res[$ti->getId()] = [
                    'label'   => $ti->getLibelle(),
                    'options' => [],
                ];
            }
            $res[$ti->getId()]['options'][$statut->getId()] = $statut->getLibelle();
        }

        return $res;
    }



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object Intervenant */
        parent::bind($object, $flags);

        $this->protection($object);

        return $this;
    }



    /**
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }



    /**
     * @param boolean $readOnly
     *
     * @return Dossier
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
    }



    public function protection($object)
    {
        if ($this->isReadOnly()) {
            foreach ($this->getElements() as $element) {
                /** @var Element $element */
                $element->setAttribute('readonly', true);
                $element->setAttribute('disabled', true);
            }
        } else {
            $noImport = ['syncStatut', 'syncStructure', 'statut', 'structure'];

            foreach ($this->getElements() as $element) {
                if (!in_array($element->getName(), $noImport)) {
                    /** @var Element $element */
                    $element->removeAttribute('readonly');
                    $element->removeAttribute('disabled');
                    $element->removeAttribute('title');
                }
            }
            if ($object && $object->getSource() && $object->getSource()->getImportable()) {
                foreach ($this->getElements() as $element) {
                    /** @var Element $element */
                    if ($this->getServiceSchema()->isImportedProperty($object, $element->getName()) && !in_array($element->getName(), $noImport)) {
                        $element->setAttribute('readonly', true);
                        $element->setAttribute('disabled', true);
                        $element->setAttribute('title', 'Champ intialisé à partir de ' . $object->getSource());
                        $this->getHydrator()->setReadonly($element->getName(), true);
                    }
                }
            }
        }
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
            'montantIndemniteFc' => [
                'required' => false,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ],
            'civilite'           => ['required' => false],
            'nomUsuel'           => ['required' => true],
            'nomPatronymique'    => ['required' => false],
            'prenom'             => ['required' => true],
            'dateNaissance'      => ['required' => true],
            'statut'             => ['required' => false],
            'structure'          => ['required' => false],
            'discipline'         => ['required' => false],
            'grade'              => ['required' => false],
            'code'               => ['required' => true],
            'utilisateurCode'    => ['required' => false],
            'source'             => ['required' => false],
            'sourceCode'         => ['required' => false],
            'montantIndemniteFc' => ['required' => false],
        ];
    }
}
