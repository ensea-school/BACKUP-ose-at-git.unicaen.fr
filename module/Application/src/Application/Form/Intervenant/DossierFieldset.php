<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Dossier as Dossier;
use Application\Entity\Db\StatutIntervenant;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DepartementServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Validator\DepartementNaissanceValidator;
use Application\Validator\NumeroINSEEValidator;
use Application\Validator\PaysNaissanceValidator;
use Application\Constants;
use Application\Validator\RIBValidator;
use DoctrineModule\Form\Element\Proxy;
use DoctrineORMModule\Form\Element\EntitySelect;
use Zend\Validator\Date as DateValidator;

/**
 * Description of DossierFieldset
 *
 */
class DossierFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use CiviliteServiceAwareTrait;

    static private $franceId;



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $hydrator = new DossierFieldsetDoctrineHydrator($this->getServiceContext()->getEntityManager());

        $this
            ->setObject(new Dossier())
            ->setHydrator($hydrator)
            ->addElements();
    }



    /**
     * @return self
     */
    private function addElements()
    {
        /**
         * Id
         */
        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        /**
         * Nom usuel
         */
        $this->add([
            'name'    => 'nomUsuel',
            'options' => [
                'label' => 'Nom usuel',
            ],
            'type'    => 'Text',
        ]);

        /**
         * Nom patro
         */
        $this->add([
            'name'    => 'nomPatronymique',
            'options' => [
                'label' => 'Nom de naissance',
            ],
            'type'    => 'Text',
        ]);

        /**
         * Prénom
         */
        $this->add([
            'name'    => 'prenom',
            'options' => [
                'label' => 'Prénom',
            ],
            'type'    => 'Text',
        ]);

        /**
         * Civilité
         */
        $civilite = new EntitySelect('civilite', [
            'label'        => 'Civilité',
            'empty_option' => "(Sélectionnez une civilité...)",
        ]);
        $civilite->getProxy()
            ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['libelleLong' => 'ASC']]])
            ->setObjectManager($this->getServiceContext()->getEntityManager())
            ->setTargetClass(\Application\Entity\Db\Civilite::class);
        $this->add($civilite);


        /**
         * Date de naissance
         */
        $this->add([
            'name'       => 'dateNaissance',
            'options'    => [
                'label'         => 'Date de naissance',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'placeholder' => "jj/mm/aaaa",
            ],
            'type'       => 'UnicaenApp\Form\Element\Date',
        ]);

        /**
         * Pays de naissance
         */
        $paysSelect = new PaysSelect('paysNaissance', [
            'label'        => 'Pays de naissance',
            'empty_option' => "(Sélectionnez un pays...)",
        ]);
        $paysSelect->getProxy()
            ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['libelle' => 'ASC']]])
            ->setObjectManager($this->getServiceContext()->getEntityManager())
            ->setTargetClass(\Application\Entity\Db\Pays::class);
        foreach ($paysSelect->getProxy()->getObjects() as $p) {
            $estFrance = $p->isFrance();
            if ($estFrance) {
                self::$franceId = $p->getId();
            }
        }
        $paysSelect->setValue(self::$franceId);
        $this->add($paysSelect);

        /**
         * Département de naissance
         */
        $departementSelect = new EntitySelect('departementNaissance', [
            'label'        => 'Département de naissance',
            'empty_option' => "(Sélectionnez un département...)",
        ]);
        $departementSelect->getProxy()
            ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['code' => 'ASC']]])
            ->setObjectManager($this->getServiceContext()->getEntityManager())
            ->setTargetClass(\Application\Entity\Db\Departement::class);
        $this->add($departementSelect);

        /**
         * Ville de naissance
         */
        $this->add([
            'name'    => 'villeNaissance',
            'options' => [
                'label' => 'Ville de naissance',
            ],
            'type'    => 'Text',
        ]);

        /**
         * Numéro INSEE
         */

        /**
         * Adresse postale
         */
        $this->add([
            'name'       => 'adresse',
            'options'    => [
                'label'         => 'Adresse postale <em>en France</em>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'rows' => 5,
            ],
            'type'       => 'Textarea',
        ]);

        /**
         * Email pro
         */
        $this->add([
            'name'       => 'email',
            'options'    => [
                'label' => 'Adresse mail établissement',
            ],
            'attributes' => [
                'readonly' => true,
            ],
            'type'       => 'Text',
        ]);

        /**
         * Email perso
         */
        $this->add([
            'name'       => 'emailPerso',
            'options'    => [
                'label' => 'Adresse mail personnelle (éventuelle)',
            ],
            'attributes' => [
                'info_icon' => "Si vous renseignez une adresse mail perso, celle-ci sera utilisée pour vous contacter.",
                'readonly'  => false,
            ],
            'type'       => 'Text',
        ]);

        /**
         * Téléphone
         */
        $this->add([
            'name'       => 'telephone',
            'options'    => [
                'label' => 'Téléphone',
            ],
            'attributes' => [
                'size' => 13,
            ],
            'type'       => 'Text',
        ]);


        $this->add([
            'name'       => 'ribBic',
            'options'    => [
                'label' => 'BIC',
            ],
            'attributes' => [
                'size'      => 11,
                'maxlength' => 11,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'ribIban',
            'options'    => [
                'label' => 'IBAN',
            ],
            'attributes' => [
                'size'      => 34,
                'maxlength' => 34,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'    => 'ribHorsSepa',
            'options' => [
                'label' => 'RIB hors zone SEPA',
            ],
            'type'    => 'Checkbox',
        ]);

        /**
         * 1er recrutement
         */
        $annee = ((int)$this->getServiceContext()->getAnnee()->getDateDebut()->format('Y')) - 2;
        $etabl = $this->getServiceContext()->getEtablissement();
        $this->add([
            'name'       => 'premierRecrutement',
            'options'    => [
                'empty_option'  => "(Sélectionnez...)",
                'label'         => "Avez-vous exercé une activité rémunérée à l'$etabl depuis le 01/09/$annee ?",
                'value_options' => [ // ATTENTION! La logique de la question s'est inversée !
                                     '0' => "Oui",    //   "Oui, j'ai déjà enseigné"   <=> premierRecrutement = 0
                                     '1' => "Non",    //   "Non, je n'ai pas enseigné" <=> premierRecrutement = 1
                ],
            ],
            'attributes' => [
            ],
            'type'       => 'Radio',
        ]);

        /**
         * Statut intervenant
         */
        $statut = new StatutSelect('statut', [
            'label'        => "Quel est votre statut ?",
            'empty_option' => "(Sélectionnez...)",
            'value'        => '',
        ]);
        $statut->getProxy()
            ->setFindMethod([
                'name'   => 'findBy',
                'params' => [
                    'criteria' => ['peutChoisirDansDossier' => true],
                    'orderBy'  => ['ordre' => 'ASC'],
                ],
            ])
            ->setObjectManager($this->getServiceContext()->getEntityManager())
            ->setTargetClass(\Application\Entity\Db\StatutIntervenant::class);
        $this->add($statut);

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
        $paysNaissanceId       = (int)$this->get('paysNaissance')->getValue();
        $numeroInseeProvisoire = (bool)$this->get('numeroInseeEstProvisoire')->getValue();

        // la sélection du département n'est obligatoire que si le pays sélectionné est la France
        $departementRequired = (self::$franceId === $paysNaissanceId);

       $spec = [
            'nomUsuel'             => [
                'required' => true,
            ],
            'nomPatronymique'      => [
                'required' => false,
            ],
            'prenom'               => [
                'required' => true,
            ],
            'civilite'             => [
                'required' => true,
            ],
            'dateNaissance'        => [
                'required'   => true,
                'validators' => [
                    new DateValidator(['format' => Constants::DATE_FORMAT]),
                ],
            ],
            'paysNaissance'        => [
                'required'   => true,
                'validators' => [
                    new PaysNaissanceValidator(['service' => $this->getServicePays()]),
                ],
            ],
            'departementNaissance' => [
                'required'   => $departementRequired,
                'validators' => [
                    new DepartementNaissanceValidator(),
                ],
            ],
            'villeNaissance'       => [
                'required' => true,
            ],
            'numeroInsee'          => [
                'required'   => true,
                'validators' => [
                    new NumeroINSEEValidator([
                        'provisoire' => $numeroInseeProvisoire,
                    ]),
                ],
            ],
            'adresse'              => [
                'required' => true,
            ],
            'email'                => [
                'required'   => false,
                'filters'    => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress'],
                ],
            ],
            'emailPerso'           => [
                'required'   => false,
                'filters'    => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress'],
                ],
            ],
            'telephone'            => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
            ],

            'ribBic' => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToUpper'],
                ],
                'validators' => [
                    new \Zend\Validator\Regex([
                        'pattern'  => "/[0-9a-zA-Z]{8,11}/",
                        'messages' => [\Zend\Validator\Regex::NOT_MATCH => "Le BIC doit contenir 8 à 11 caractères"],
                    ]),
                ],
            ],

            'ribIban' => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToUpper'],
                ],
                'validators' => [
                    new RIBValidator(),
                ],
            ],

            'premierRecrutement' => [
                'required' => $this->has('premierRecrutement'),
            ],
            'statut'             => [
                'required' => true,
            ],
        ];

        return $spec;
    }
}





/**
 * Select d'entités Pays, avec proxy dédié.
 */
class PaysSelect extends EntitySelect
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->proxy = new PaysProxy();
    }
}





/**
 * Proxy pour le select d'entités Pays : customisation des attributs HTML des <option>.
 */
class PaysProxy extends Proxy
{
    use PaysServiceAwareTrait;



    protected function loadValueOptions()
    {
        parent::loadValueOptions();

        foreach ($this->valueOptions as $key => $value) {
            $id        = $value['value'];
            $pays      = $this->objects[$id];
            $estFrance = $pays->isFrance();

            $this->valueOptions[$key]['attributes'] = [
                'class'      => "pays" . ($estFrance ? " france" : null),
                'data-debut' => $pays->getValiditeDebut()->format('d/m/Y'),
                'data-fin'   => $pays->getValiditeFin() ? $pays->getValiditeFin()->format('d/m/Y') : null,
            ];
        }
    }



    protected function loadObjects()
    {
        parent::loadObjects();

        // reformattage du tableau de données : id => Pays
        $pays = [];
        foreach ($this->objects as $p) {
            $pays[$p->getId()] = $p;
        }

        $this->objects = $pays;
    }
}





/**
 * Select d'entités StatutIntervenant, avec proxy dédié.
 *
 * @method StatutIntervenantProxy getProxy() Description
 */
class StatutSelect extends EntitySelect
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->proxy = new StatutIntervenantProxy();
    }
}





/**
 * Proxy pour le select d'entités StatutIntervenant :
 * - customisation des attributs HTML des <option> ;
 * - suppression des statuts à écarter ;
 * - fourniture du validateur.
 */
class StatutIntervenantProxy extends Proxy
{
    protected function loadValueOptions()
    {
        parent::loadValueOptions();

        foreach ($this->valueOptions as $key => $value) {
            $id     = $value['value'];
            $statut = $this->objects[$id];

            $this->valueOptions[$key]['attributes'] = [
                'class' => $statut->getSourceCode(),
            ];
        }
    }



    protected function loadObjects()
    {
        parent::loadObjects();

        // reformattage du tableau de données : id => Statut
        $pays = [];
        foreach ($this->objects as $o) {
            /* @var $o StatutIntervenant */
            if ($o->estNonHistorise()) {
                $pays[$o->getId()] = $o;
            }
        }

        $this->objects = $pays;
    }
}