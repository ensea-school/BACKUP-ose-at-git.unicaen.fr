<?php

namespace Application\Form\StatutIntervenant;

use Application\Form\AbstractForm;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;

/**
 * Description of StatutIntervenantSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class StatutIntervenantSaisieForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;



    public function init()
    {
        $hydrator = new StatutIntervenantHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $cases = [
            'non-autorise'                   => "Intervenant non autorisé à figurer dans OSE",
            "depassement"                    => "Dépassement autorisé du service statutaire",
            'peut-saisir-service'            => "Possibilité d'avoir des services d'enseignement",
            'peut-choisir-dans-dossier'      => "Ce statut pourra être choisi dans le dossier de l'intervenant",
            'peut-saisir-dossier'            => "L'intervenant a un dossier personnel",
            'peut-saisir-referentiel'        => "Possibilité d'avoir des heures de référentiel",
            'peut-saisir-motif-non-paiement' => "Possibilité d'avoir des heures non payables (justifiées par un motif de non paiement)",
            'peut-avoir-contrat'             => "Possibilité d'éditer un contrat ou un avenant",
            'peut-cloturer-saisie'           => "Active la clôture de saisie des heures de service réalisées",
            'peut-saisir-service-ext'        => "Possibilité de saisir des services d'enseignement sur d'autres établissements",
            'depassement-sdshc'              => "Le dépassement du service dû ne doit pas donner lieu à des heures complémentaires",
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
            'name'       => 'service-statutaire',
            'options'    => [
                'label' => "Service statutaire",
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
                'label' => "Plafond des HETD",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures de service (en équivalent TD) autorisées pour l'intervenant",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-referentiel',
            'options'    => [
                'label' => "Plafond du référentiel",
                'suffix' => 'HETD',
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures de référentiel autorisées pour l'intervenant",
            ],
            'type'       => 'Text',
        ]);


        $this->add([
            'name'       => 'plafond-h-h-c',
            'options'    => [
                'label' => "Plafond des HC (hors prime FC D714-60)",
                'suffix' => 'HETD'
            ],
            'attributes' => [
                'title' => "Nombre maximal d'heures complémentaires (hors heures relevant de la prime de formation continue au titre de l'article D714-60 du code de l'éducation)",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'plafond-h-c',
            'options'    => [
                'label' => "Plafond prime FC D714-60",
                'suffix' => '&euro;'
            ],
            'attributes' => [
                'title' => "Montant maximal de la prime de formation continue relevant de l'article D714-60 du code de l'éducation",
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
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'libelle'             => [
                'required' => true,
            ],
            'type-intervenant'    => [
                'required' => true,
            ],
            'code'                => [
                'required' => true,
            ],
            'plafond-h-c'         => [
                'required'   => true,
                'validators' => [
                    new \Zend\Validator\Callback([
                        'messages' => [\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'plafond-h-h-c'       => [
                'required'   => true,
                'validators' => [
                    new \Zend\Validator\Callback([
                        'messages' => [\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'service-statutaire'  => [
                'required'   => true,
                'validators' => [
                    new \Zend\Validator\Callback([
                        'messages' => [\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'plafond-referentiel' => [
                'required'   => true,
                'validators' => [
                    new \Zend\Validator\Callback([
                        'messages' => [\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'maximum-HETD'        => [
                'required'   => true,
                'validators' => [
                    new \Zend\Validator\Callback([
                        'messages' => [\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
        ];
    }



    public function readOnly()
    {
        /** @var $element \Zend\Form\Element */
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





class StatutIntervenantHydrator implements HydratorInterface
{

    use TypeIntervenantServiceAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                    $data
     * @param  \Application\Entity\Db\StatutIntervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDepassement($data['depassement']);
        $object->setPlafondReferentiel(isset($data['plafond-referentiel']) ? FloatFromString::run($data['plafond-referentiel']) : 0);
        $object->setServiceStatutaire(FloatFromString::run($data['service-statutaire']));
        if (array_key_exists('type-intervenant', $data)) {
            $object->setTypeIntervenant($this->getServiceTypeIntervenant()->get($data['type-intervenant']));
        }
        $object->setNonAutorise($data['non-autorise']);
        $object->setPeutSaisirService($data['peut-saisir-service']);
        $object->setPeutSaisirDossier($data['peut-saisir-dossier']);
        $object->setPeutSaisirReferentiel($data['peut-saisir-referentiel']);
        $object->setPeutSaisirMotifNonPaiement($data['peut-saisir-motif-non-paiement']);
        $object->setPeutAvoirContrat($data['peut-avoir-contrat']);
        $object->setPeutCloturerSaisie($data['peut-cloturer-saisie']);
        $object->setPeutSaisirServiceExt($data['peut-saisir-service-ext']);
        $object->setTemAtv($data['TEM-ATV']);
        $object->setTemBiatss($data['TEM-BIATSS']);
        $object->setSourceCode($data['code']);
        $object->setPlafondHcHorsRemuFc(FloatFromString::run($data['plafond-h-h-c']));
        $object->setPlafondHcRemuFc(FloatFromString::run($data['plafond-h-c']));
        $object->setPeutChoisirDansDossier($data['peut-choisir-dans-dossier']);
        $object->setMaximumHETD(FloatFromString::run($data['maximum-HETD']));
        $object->setDepassementSDSHC($data['depassement-sdshc']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\StatutIntervenant $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                             => $object->getId(),
            'libelle'                        => $object->getLibelle(),
            'depasement'                     => $object->getDepassement(),
            'service-statutaire'             => StringFromFloat::run($object->getServiceStatutaire()),
            'plafond-referentiel'            => StringFromFloat::run($object->getPlafondReferentiel()),
            'non-autorise'                   => $object->getNonAutorise(),
            'peut-saisir-service'            => $object->getPeutSaisirService(),
            'peut-saisir-referentiel'        => $object->getPeutSaisirReferentiel(),
            'peut-saisir-motif-non-paiement' => $object->getPeutSaisirMotifNonPaiement(),
            'peut-avoir-contrat'             => $object->getPeutAvoirContrat(),
            'peut-cloturer-saisie'           => $object->getPeutCloturerSaisie(),
            'peut-saisir-service-ext'        => $object->getPeutSaisirServiceExt(),
            'TEM-ATV'                        => $object->getTemAtv(),
            'TEM-BIATSS'                     => $object->getTemBiatss(),
            'type-intervenant'               => ($s = $object->getTypeIntervenant()) ? $s->getId() : null,
            'code'                           => $object->getSourceCode(),
            'plafond-h-h-c'                  => StringFromFloat::run($object->getPlafondHcHorsRemuFc()),
            'plafond-h-c'                    => $object->getPlafondHcRemuFc(),
            'maximum-HETD'                   => StringFromFloat::run($object->getMaximumHETD()),
            'depassement-sdshc'              => $object->getDepassementSDSHC(),
        ];

        return $data;
    }
}