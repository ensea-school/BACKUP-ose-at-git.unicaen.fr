<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Dossier as DossierEntity;
use Application\Entity\Db\Pays as PaysEntity;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\DepartementAwareTrait;
use Application\Service\Traits\PaysAwareTrait;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Validator\DepartementNaissanceValidator;
use Application\Validator\NumeroINSEEValidator;
use Application\Validator\PaysNaissanceValidator;
use Application\Validator\StatutIntervenantValidator;
use Common\Constants;
use Common\Exception\LogicException;
use DoctrineModule\Form\Element\Proxy;
use DoctrineORMModule\Form\Element\EntitySelect;
use Zend\Validator\Date as DateValidator;

/**
 * Description of DossierFieldset
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierFieldset extends AbstractFieldset
{
    use ContextAwareTrait;
    use StatutIntervenantAwareTrait;
    use PaysAwareTrait;
    use DepartementAwareTrait;

    static private $franceId;
    
    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $hydrator = new DossierFieldsetDoctrineHydrator($this->getServiceContext()->getEntityManager());
        
        $this
                ->setObject(new DossierEntity())
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
            'type' => 'Hidden'
        ]);

        /**
         * Nom usuel
         */
        $this->add([
            'name'    => 'nomUsuel',
            'options' => [
                'label' => 'Nom usuel',
            ],
            'type'    => 'Text'
        ]);

        /**
         * Nom patro
         */
        $this->add([
            'name'    => 'nomPatronymique',
            'options' => [
                'label' => 'Nom de naissance',
            ],
            'type'    => 'Text'
        ]);

        /**
         * Prénom
         */
        $this->add([
            'name'    => 'prenom',
            'options' => [
                'label' => 'Prénom',
            ],
            'type'    => 'Text'
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
            'options'    => array(
                'label'         => 'Date de naissance',
                'label_options' => array(
                    'disable_html_escape' => true
                ),
            ),
            'attributes' => array(
                'placeholder' => "jj/mm/aaaa",
            ),
            'type'  => 'UnicaenApp\Form\Element\Date',
        ]);

        /**
         * Pays de naissance
         */
        $paysSelect = new PaysSelect('paysNaissance', [
            'label'        => 'Pays de naissance',
            'empty_option' => "(Sélectionnez un pays...)"
        ]);
        $paysSelect->getProxy()
                ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['libelleLong' => 'ASC']]])
                ->setObjectManager($this->getServiceContext()->getEntityManager())
                ->setTargetClass(\Application\Entity\Db\Pays::class);
        foreach ($paysSelect->getProxy()->getObjects() as $p) {
            $estFrance = PaysEntity::CODE_FRANCE === $p->getSourceCode();
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
            'empty_option' => "(Sélectionnez un département...)"
        ]);
        $departementSelect->getProxy()
                ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['sourceCode' => 'ASC']]])
                ->setObjectManager($this->getServiceContext()->getEntityManager())
                ->setTargetClass(\Application\Entity\Db\Departement::class);
        $this->add($departementSelect);

        /**
         * Ville de naissance
         */
        $this->add(array(
            'name'    => 'villeNaissance',
            'options' => array(
                'label' => 'Ville de naissance',
            ),
            'type'    => 'Text'
        ));

        /**
         * Numéro INSEE
         */
        $this->add([
            'name'       => 'numeroInsee',
            'options'    => [
                'label'              => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> (clé incluse)',
                'use_hidden_element' => false,
                'checked_value'      => 1,
                'unchecked_value'    => 0,
                'label_options'      => [
                    'disable_html_escape' => true
                ],
            ],
            'attributes' => [
                'info_icon' => "Numéro INSEE (sécurité sociale) avec la clé de contrôle",
            ],
            'type'       => 'Text',
        ]);

        /**
         * Numéro INSEE provisoire
         */
        $this->add([
            'name'       => 'numeroInseeEstProvisoire',
            'options'    => [
                'label'         => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> provisoire',
                'label_options' => [
                    'disable_html_escape' => true
                ],
            ],
            'attributes' => [
            ],
            'type'       => 'Checkbox',
        ]);

        /**
         * Adresse postale
         */
        $this->add([
            'name'       => 'adresse',
            'options'    => [
                'label'         => 'Adresse postale <em>en France</em>',
                'label_options' => array(
                    'disable_html_escape' => true
                ),
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
                'readonly' => true
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
                'info_icon' => "Si vous renseignez une adresse mail perso, celle-ci sera uttilisée pour vous contacter.",
                'readonly' => false
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

        /**
         * RIB
         */
        $this->add([
            'name'       => 'rib',
            'options'    => [
                'label' => 'RIB du <em>compte personnel</em>',
            ],
            'attributes' => [
            ],
            'type'       => 'UnicaenApp\Form\Element\RIBFieldset',
        ]);

        /**
         * 1er recrutement
         */
        $annee = ((int) $this->getServiceContext()->getAnnee()->getDateDebut()->format('Y')) - 2;
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
        $paysNaissanceId       = (int) $this->get('paysNaissance')->getValue();
        $numeroInseeProvisoire = (bool) $this->get('numeroInseeEstProvisoire')->getValue();
        $statutSelect          = $this->get('statut'); /* @var $statutSelect StatutSelect */
        
        // la sélection du département n'est obligatoire que si le pays sélectionné est la France
        $departementRequired = (self::$franceId === $paysNaissanceId);

        $spec = [
            'nomUsuel' => [
                'required' => true,
            ],
            'nomPatronymique' => [
                'required' => true,
            ],
            'prenom' => [
                'required' => true,
            ],
            'civilite' => [
                'required' => true,
            ],
            'dateNaissance' => array(
                'required' => true,
                'validators' => array(
                    new DateValidator(array('format' => Constants::DATE_FORMAT)),
                ),
            ),
            'paysNaissance' => array(
                'required' => true,
                'validators' => array(
                    new PaysNaissanceValidator(['service' => $this->getServicePays()]),
                ),
            ),
            'departementNaissance' => array(
                'required' => $departementRequired,
                'validators' => array(
                    new DepartementNaissanceValidator(['france_id' => self::$franceId]),
                ),
            ),
            'villeNaissance' => array(
                'required' => true,
            ),
            'numeroInsee' => array(
                'required' => true,
                'validators' => array(
                    new NumeroINSEEValidator([
                        'provisoire' => $numeroInseeProvisoire, 
                        'france_id'  => self::$franceId,
                        'service'    => $this->getServiceDepartement(),
                    ]),
                ),
            ),
            'adresse' => [
                'required' => true,
            ],
            'email' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress']
                ],
            ],
            'emailPerso' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress']
                ],
            ],
            'telephone' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
//                    new \Zend\I18n\Validator\PhoneNumber(), // les formats de numéros ne tolèrent pas le 0 de tête!!
                ],
            ],
            'premierRecrutement' => [
                'required' => $this->has('premierRecrutement'),
            ],
            'statut' => [
                'required' => true,
                'validators' =>  [
                    $statutSelect->getProxy()->getValidator(),
                ],
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
    public function __construct($name = null, $options = array())
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
    protected function loadValueOptions()
    {
        parent::loadValueOptions();
        
        foreach ($this->valueOptions as $key => $value) {
            $id        = $value['value'];
            $pays      = $this->objects[$id];
            $estFrance = PaysEntity::CODE_FRANCE === $pays->getSourceCode();
            
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
    public function __construct($name = null, $options = array())
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
            // suppression des statuts à écarter
            if (in_array($o, $this->statutsToRemove)) {
                continue;
            }
            $pays[$o->getId()] = $o;
        }
        
        $this->objects = $pays;
    }
    
    /**
     * @var StatutIntervenantEntity[]
     */
    private $statutsToRemove = [];
    
    /**
     * Statuts à écarter.
     * 
     * @param StatutIntervenantEntity[] $statuts 
     * @return self
     */
    public function setStatutsToRemove(array $statuts)
    {
        $this->statutsToRemove = [];
        
        foreach ($statuts as $statut) {
            if (! $statut instanceof StatutIntervenantEntity) {
                throw new LogicException("Les statuts à écarter doivent être spécifiés sous forme d'objets.");
            }
            $this->statutsToRemove[$statut->getId()] = $statut;
        }
        
        $this->objects = []; // force objects reload
        
        return $this;
    }
    
    /**
     * 
     * @return StatutIntervenantValidator
     */
    public function getValidator()
    {
        $v = new StatutIntervenantValidator();
        $v->setStatutsInterdits($this->statutsToRemove);
        
        return $v;
    }
}