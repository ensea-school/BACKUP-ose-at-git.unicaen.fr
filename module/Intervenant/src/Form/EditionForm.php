<?php

namespace Intervenant\Form;

use Application\Filter\FloatFromString;
use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Unicaen\Framework\Application\Application;
use Intervenant\Entity\Db\Civilite;
use Intervenant\Entity\Db\Grade;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Intervenant\Service\CiviliteServiceAwareTrait;
use Intervenant\Service\GradeServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Form\Element;
use Laminas\Form\Element\Email;
use Laminas\Form\FormInterface;
use Laminas\Validator\EmailAddress;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\Discipline;
use OffreFormation\Service\Traits\DisciplineServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\Util;
use UnicaenImport\Entity\Db\Source;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;
use Utilisateur\Connecteur\LdapConnecteurAwareTrait;
use Utilisateur\Service\UtilisateurServiceAwareTrait;

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
    use StatutServiceAwareTrait;
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
        'numeroInsee'        => ['type' => 'string'],
        'numeroPec'          => ['type' => 'string'],
        'dateNaissance'      => ['type' => \DateTime::class],
        'statut'             => ['type' => Statut::class],
        'structure'          => ['type' => Structure::class],
        'discipline'         => ['type' => Discipline::class],
        'grade'              => ['type' => Grade::class],
        'code'               => ['type' => 'string'],
        'codeRh'             => ['type' => 'string'],
        'utilisateurCode'    => ['type' => 'string'],
        'emailPerso'         => ['type' => 'string'],
        'source'             => ['type' => Source::class],
        'sourceCode'         => ['type' => 'string'],
        'syncStatut'         => ['type' => 'bool'],
        'syncStructure'      => ['type' => 'bool'],
        'syncPec'            => ['type' => 'bool'],
        'irrecevable'        => ['type' => 'bool'],
        'montantIndemniteFc' => ['type' => 'float'],
        'validiteDebut'      => ['type' => \DateTime::class],
        'validiteFin'        => ['type' => \DateTime::class],
    ];

    protected $readOnly = false;



    public function init()
    {
        $hydrator = new EditionFormHydrator($this->getServiceSource()->getEntityManager(), $this->hydratorElements);
        $hydrator->spec(Intervenant::class);
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
            'name'    => 'dateNaissance',
            'type'    => 'Date',
            'options' => [
                'label'         => 'Date de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
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
            'name'    => 'structure',
            'type'    => \Lieu\Form\Element\Structure::class,
            'options' => [
                'label' => 'Structure',
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
            'name'    => 'codeRh',
            'type'    => 'Text',
            'options' => [
                'label' => 'Code RH éventuel',
            ],
        ]);

        $this->add([
            'name'    => 'numeroInsee',
            'type'    => 'Text',
            'options' => [
                'label'         => 'Numéro INSEE',
                'label_options' => ['disable_html_escape' => true],
            ],

        ]);

        $this->add([
            'name'    => 'numeroPec',
            'type'    => 'Text',
            'options' => [
                'label'         => 'Numéro de prise en charge',
                'label_options' => ['disable_html_escape' => true],
            ],

        ]);

        $this->add([
            'name'       => 'emailPerso',
            'type'       => Email::class,
            'options'    => [
                'label' => 'E-mail personnel',
                //'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                //'placeholder' => "Email établissement",
                //'class'     => 'form-control left-border-none dossierElement',
            ],
            'type'       => Email::class,
        ]);

        $utilisateur = new SearchAndSelect('utilisateur');
        $utilisateur->setRequired(false)
            ->setSelectionRequired(false)
            ->setAutocompleteSource(
                $this->getUrl('utilisateur-recherche')
            )
            ->setLabel("Utilisateur")
            ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);

        $this->add($utilisateur);

        $this->add([
            'name'       => 'intervenant-edition-login',
            'type'       => 'Text',
            'attributes' => [
                'autocomplete' => 'off',
                'readonly'     => 'true',
            ],
            'options'    => [
                'label' => 'Login',
            ],

        ]);

        $this->add([
            'name'       => 'intervenant-edition-password',
            'type'       => 'Password',
            'attributes' => [
                'autocomplete' => 'off',
                'readonly'     => 'true',
            ],
            'options'    => [
                'label' => 'Mot de passe (6 caractères min.)',
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
            'name'    => 'validiteDebut',
            'type'    => 'Date',
            'options' => [
                'label' => 'Début de validité',
            ],
        ]);

        $this->add([
            'name'    => 'validiteFin',
            'type'    => 'Date',
            'options' => [
                'label' => 'Fin de validité',
            ],
        ]);

        $this->add([
            'name'    => 'dateNaissance',
            'type'    => 'Date',
            'options' => [
                'label'         => 'Date de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
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
            'name'       => 'syncPec',
            'options'    => [
                'label' => 'Synchronisation du numéro PEC',
            ],
            'attributes' => [
                'title' => 'Si pas coché, alors le numéro de prise en charge ne sera plus définie par le connecteur, ni par le chargement en masse des numéros de prise en charge',
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'irrecevable',
            'options' => [
                'label' => 'Fiche considérée comme irrecevable (sortie des indicateurs)',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'userChange',
            'type'       => 'Hidden',
            'attributes' => ['value' => '0'],
        ]);

        $this->add([
            'name'       => 'userCreate',
            'type'       => 'Hidden',
            'attributes' => ['value' => '0'],
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
        $statuts = $this->getServiceStatut()->getStatuts();
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



    public function activerEditionAvancee()
    {
        $elements = ['source', 'code', 'numeroPec', 'numeroInsee'];
        foreach ($this->getElements() as $element) {
            if (in_array($element->getName(), $elements)) {

                /** @var Element $element */
                $element->removeAttribute('readonly');
                $element->removeAttribute('disabled');
                $element->removeAttribute('title');
            }
        }
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
            $noImport = ['syncStatut', 'syncStructure', 'syncPec', 'statut', 'structure', 'intervenant-edition-login', 'intervenant-edition-password'];
            if ($object->getAnnee()->getId() < $this->getServiceContext()->getAnneeImport()->getId()) {
                $noImport[] = 'grade';
            }

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
            if (!$object->getId()) {
                $syncElements = ['syncStatut', 'syncStructure', 'source', 'sourceCode'];
                foreach ($syncElements as $elementName) {
                    $element = $this->get($elementName);

                    /** @var Element $element */
                    $element->setAttribute('readonly', true);
                    $element->setAttribute('disabled', true);
                    $element->setAttribute('title', 'Paramètres de synchronisation inutiles dans le cas de la création d\'une nouvelle fiche');
                }
            }
        }
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
            'montantIndemniteFc'           => [
                'required' => false,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ],
            'civilite'                     => ['required' => false],
            'nomUsuel'                     => ['required' => true],
            'nomPatronymique'              => ['required' => false],
            'prenom'                       => ['required' => true],
            'dateNaissance'                => ['required' => true],
            'emailPerso'                   => [
                'required'   => false,
                'validators' => [
                    ['name' => EmailAddress::class],
                ],
            ],
            'statut'                       => ['required' => false],
            'structure'                    => ['required' => false],
            'discipline'                   => ['required' => false],
            'grade'                        => ['required' => false],
            'code'                         => ['required' => true],
            'codeRh'                       => ['required' => false],
            'numeroInsee'                  => ['required' => false],
            'numeroPec'                    => ['required' => false],
            'utilisateur'                  => ['required' => false],
            'intervenant-edition-login'    => ['required' => false],
            'validiteDebut'                => ['required' => false],
            'validiteFin'                  => ['required' => false],
            'intervenant-edition-password' => [
                'required'   => false,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => ['min' => 6],
                    ],
                ],
                'filters'    => [
                    ['name' => 'StringTrim'],
                ],
            ],
            'source'                       => ['required' => false],
            'sourceCode'                   => ['required' => false],
        ];
    }
}


class EditionFormHydrator extends GenericHydrator
{
    use UtilisateurServiceAwareTrait;
    use LdapConnecteurAwareTrait;

    protected $noGenericParse = ['utilisateur', 'creerUtilisateur', 'entityManager'];



    /**
     * @param array                              $data
     * @param \Intervenant\Entity\Db\Intervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        parent::hydrate($data, $object);

        if ($data['userChange'] == '1') {
            $login = isset($data['utilisateur']['id']) ? $data['utilisateur']['id'] : null;
            if ($login) {
                $code = $this->getConnecteurLdap()->getCodeFromLogin($login);
            } else {
                $code = null;
            }

            $object->setUtilisateurCode($code);
            $object->setSyncUtilisateurCode(false);
        }
    }



    /**
     * @param \Intervenant\Entity\Db\Intervenant $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $res = parent::extract($object);

        $res['creerUtilisateur'] = !$object->getId() && !Application::getInstance()->config()['cas']['actif'] ?? false;

        if ($code = $object->getUtilisateurCode()) {
            $utilisateur = $this->getConnecteurLdap()->getUtilisateurFromCode($code, false);
            if ($utilisateur) {
                $res['utilisateur'] = [
                    'id'    => $utilisateur->getUsername(),
                    'label' => (string)$utilisateur,
                ];
            }
        }

        return $res;
    }
}