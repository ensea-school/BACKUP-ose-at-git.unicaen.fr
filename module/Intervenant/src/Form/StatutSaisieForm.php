<?php

namespace Intervenant\Form;

use Application\Form\AbstractForm;
use Intervenant\Hydrator\StatutIntervenantHydrator;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\TypeAgrementServiceAwareTrait;
use Application\Service\Traits\TypeAgrementStatutServiceAwareTrait;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Application\Filter\FloatFromString;

/**
 * Description of StatutSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class StatutSaisieForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;
    use TypeAgrementServiceAwareTrait;
    use TypeAgrementStatutServiceAwareTrait;
    use DossierAutreServiceAwareTrait;
    use ParametresServiceAwareTrait;


    public function init()
    {
        $hydrator = new StatutIntervenantHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $cases = [
            'non-autorise'                    => "Intervenant non autorisé à figurer dans OSE",
            "depassement"                     => "Dépassement autorisé du service statutaire",
            'peut-saisir-service'             => "Possibilité d'avoir des services d'enseignement",
            'peut-choisir-dans-dossier'       => "Ce statut pourra être choisi dans le dossier de l'intervenant",
            'peut-saisir-dossier'             => "L'intervenant a un dossier personnel",
            'peut-saisir-referentiel'         => "Possibilité d'avoir des heures de référentiel",
            'peut-saisir-motif-non-paiement'  => "Possibilité d'avoir des heures non payables (justifiées par un motif de non paiement)",
            'peut-avoir-contrat'              => "Possibilité d'éditer un contrat ou un avenant",
            'peut-cloturer-saisie'            => "Active la clôture de saisie des heures de service réalisées",
            'peut-saisir-service-ext'         => "Possibilité de saisir des services d'enseignement sur d'autres établissements",
            'depassement-sdshc'               => "Le dépassement du service dû ne doit pas donner lieu à des heures complémentaires",
            'dossier-identite-complementaire' => "Information d'identité complémentaire (Date de naissance, pays de naissance etc...)",
            'dossier-adresse'                 => "Information d'adresse ",
            'dossier-contact'                 => "Information contact (Email, téléphone etc...)",
            'dossier-insee'                   => "Information INSEE",
            'dossier-iban'                    => "Information bancaire (IBAN, BIC)",
            'dossier-employeur'               => "Information employeur",
            'prioritaire-indicateurs'         => "Mise en avant du statut en priorité dans les indicateurs",
        ];

        foreach ($cases as $key => $label) {
            $this->add([
                'name'    => $key,
                'options' => [
                    'label'              => $label,
                    'use_hidden_element' => true,
                ],
                'type'    => 'Checkbox',
            ]);
        }

        $champsAutres = $this->getServiceDossierAutre()->getList();
        if ($champsAutres) {
            foreach ($champsAutres as $autre) {
                $this->add([
                    'name'    => 'champ-autre-' . $autre->getId(),
                    'options' => [
                        'label'              => $autre->getLibelle(),
                        'use_hidden_element' => true,
                    ],
                    'type'    => 'Checkbox',
                ]);
            }
        }

        //Gestion des règles informations contact sur le dossier intervenant
        $this->add([
            'name'    => 'dossier-email-perso',
            'options' => [
                'label'              => "Email personnel obligatoire même si l'email établissement est renseigné.",
                'use_hidden_element' => true,
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'dossier-tel-perso',
            'options' => [
                'label'              => "Téléphone personnel obligatoire même si le téléphone pro est renseigné.",
                'use_hidden_element' => true,
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'id',
            'options'    => [
                'label' => "id",
            ],
            'attributes' => [
            ],
            'type'       => 'Hidden',
        ]);

        $this->add([
            'name'       => 'TEM-ATV',
            'options'    => [
                'label'              => '<abbr title="Définit si ce statut est propre aux ATV (Attaché Temporaire Vacataire)">ATV</abbr>',
                'label_options'      => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => 'ATV',
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'TEM-VA',
            'options'    => [
                'label'              => '<abbr title="Définit si ce statut est un type d\'emploi vacataire administratif.">VA</abbr>',
                'label_options'      => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => 'VA',
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'TEM-BIATSS',
            'options'    => [
                'label'              => '<abbr title="Définit si ce statut est propre aux BIATSS">BIATSS</abbr>',
                'label_options'      => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
            ],
            'attributes' => [
                'title' => 'BIATSS',
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'type-intervenant',
            'options'    => [
                'label' => 'Type d\'intervenant',
            ],
            'attributes' => [
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'libelle',
            'options'    => [
                'label' => "Libellé",
            ],
            'attributes' => [
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'code',
            'options'    => [
                'label' => "Code",
            ],
            'attributes' => [
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'code_rh',
            'options'    => [
                'label' => "Code RH",

            ],
            'attributes' => [
            ],
            'type'       => 'Text',
        ]);

        //Gestion des agréments de façon dynamique par rapport au contenu de la table type_agrement
        $qb            = $this->getServiceTypeAgrement()->finderByHistorique();
        $typesAgrement = $this->getServiceTypeAgrement()->getList($qb);

        foreach ($typesAgrement as $type) {
            $this->add([
                'name'       => $type->getCode(),
                'options'    => [
                    'label'         => $type->getLibelle(),
                    'value_options' => [
                        0 => 'Non',
                        1 => 'Oui',
                    ],
                ],
                'attributes' => [
                    'value' => 0,
                ],
                'type'       => 'Laminas\Form\Element\Radio',
            ]);

            $this->add([
                'name'       => $type->getCode() . '-DUREE_VIE',
                'options'    => [
                    'suffix' => 'an(s)',
                ],
                'attributes' => [
                    'title' => "Nombre d'annnée de validité de l'agrément",
                    'value' => '1',
                ],
                'type'       => 'Text',

            ]);
        }


        for ($i = 1; $i < 5; $i++) {
            if ($plib = $this->getServiceParametres()->get("statut_intervenant_codes_corresp_$i" . "_libelle")) {
                $this->add([
                    'name'    => "codes-corresp-$i",
                    'options' => [
                        'label' => $plib,
                    ],
                    'type'    => 'Text',
                ]);
            }
        }


        $this->add([
            'name'       => 'service-statutaire',
            'options'    => [
                'label'  => "Service statutaire",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre d'heures équivalent TD relevant du service statutaire de l'intervenant",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'maximum-HETD',
            'options'    => [
                'label'  => "Plafond des HETD",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures (en équivalent TD) autorisées pour l'intervenant, service et complémentaire",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'charges-patronales',
            'options'    => [
                'label'  => "Taux de charges patronales",
                'suffix' => '%',
            ],
            'attributes' => [
                'title' => "Taux de charges patronales exprimé en pourcentage",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-referentiel',
            'options'    => [
                'label'  => "Plafond du référentiel",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures de référentiel autorisées pour l'intervenant",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-referentiel-service',
            'options'    => [
                'label'  => "Plafond du référentiel en service",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures de référentiel autorisées en service pour l'intervenant",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-referentiel-hc',
            'options'    => [
                'label'  => "Plafond du référentiel en HC",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures de référentiel autorisées en heures complémentaires pour l'intervenant",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-h-h-c',
            'options'    => [
                'label'  => "Plafond des HC (hors prime FC D714-60)",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures complémentaires (hors heures relevant de la prime de formation continue au titre de l'article D714-60 du code de l'éducation)",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-h-c',
            'options'    => [
                'label'  => "Plafond prime FC D714-60",
                'suffix' => '&euro;',
            ],
            'attributes' => [
                'title' => "Montant maximal de la prime de formation continue relevant de l'article D714-60 du code de l'éducation",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-hc-fi-hors-ead',
            'options'    => [
                'label'  => "Plafond HC en FI hors EAD",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures complémentaires en formation initiale hors enseignement à distance",
            ],
            'type'       => 'Text',
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
        // peuplement liste des types d'intervenants
        $this->get('type-intervenant')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeIntervenant()->getList()));

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
            'libelle'                => [
                'required' => true,
            ],
            'type-intervenant'       => [
                'required' => true,
            ],
            'code'                   => [
                'required' => true,
            ],
            'code_rh'                => [
                'required' => false,
            ],
            'plafond-h-c'            => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'plafond-h-h-c'          => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'service-statutaire'     => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'plafond-referentiel'    => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'maximum-HETD'           => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'charges-patronales'     => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'plafond-hc-fi-hors-ead' => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'champ-autre-1'          => [
                'required' => false,
            ],
            'champ-autre-2'          => [
                'required' => false,
            ],
            'champ-autre-3'          => [
                'required' => false,
            ],
            'champ-autre-4'          => [
                'required' => false,
            ],
            'champ-autre-5'          => [
                'required' => false,
            ],
        ];
    }



    public function readOnly()
    {
        /** @var $element \Laminas\Form\Element */
        foreach ($this->getElements() as $element) {
            switch (get_class($element)) {
                case Number::class:
                case Text::class:
                    $element->setAttribute('readonly', true);
                break;
                case Select::class:
                case Checkbox::class:
                    $element->setAttribute('disabled', true);
                break;
            }
        }
    }
}
