<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Form\AbstractForm;
use Application\Form\Element\StatutIntervenantSelect;
use Application\Form\Employeur\EmployeurFieldset;
use Application\Form\Adresse\AdresseFieldset;
use Application\Form\Intervenant\Dossier\DossierAutresFieldset;
use Application\Form\Intervenant\Dossier\DossierBancaireFieldset;
use Application\Form\Intervenant\Dossier\DossierContactFieldset;
use Application\Form\Intervenant\Dossier\DossierIdentiteFieldset;
use Application\Form\Intervenant\Dossier\DossierInseeFieldset;
use Application\Hydrator\IntervenantDossierHydrator;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Zend\Form\Element;
use Zend\Form\Element\Csrf;
use Zend\Form\Fieldset;

/**
 * Formulaire de modification du dossier d'un intervenant extérieur.
 *
 */
class IntervenantDossierForm extends AbstractForm
{
    use StatutIntervenantServiceAwareTrait;
    use ContextServiceAwareTrait;
    use DossierServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use DossierServiceAwareTrait;

    protected $dossierIdentiteFieldset;

    protected $dossierAdresseFieldset;

    protected $dossierContactFiedlset;

    protected $dossierInseeFiedlset;

    protected $dossierBancaireFieldset;

    protected $dossierEmployeurFieldset;

    protected $dossierAutresFiedlset;

    protected $serviceAuthorize;

    protected $intervenant;

    /**
     * @var boolean
     */
    protected $readOnly;



    public function __construct(Intervenant $intervenant)
    {
        $this->serviceAuthorize = $this->getServiceContext()->getAuthorize();
        $this->intervenant      = $intervenant;
        parent::__construct('IntervenantDossierForm', []);
    }



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {

        $statutIntervenant = $this->intervenant->getStatut();

        $haveContact      = $statutIntervenant->getDossierContact();
        $serviceAuthorize = $this->getServiceContext()->getAuthorize();
        $role             = $this->getServiceContext()->getUtilisateur()->getRoles();
        $hydrator         = new IntervenantDossierHydrator();
        $this->setHydrator($hydrator);

        $this->dossierIdentiteFieldset = new DossierIdentiteFieldset('DossierIdentite');
        $this->dossierIdentiteFieldset->init();

        $this->dossierAdresseFieldset = new AdresseFieldset('DossierAdresse');
        $this->dossierAdresseFieldset->init();

        $this->dossierContactFiedlset = new DossierContactFieldset('DossierContact');
        $this->dossierContactFiedlset->init();

        $this->dossierInseeFiedlset = new DossierInseeFieldset('DossierInsee');
        $this->dossierInseeFiedlset->init();

        $this->dossierBancaireFieldset = new DossierBancaireFieldset('DossierBancaire');
        $this->dossierBancaireFieldset->init();

        $this->dossierEmployeurFieldset = new EmployeurFieldset('DossierEmployeur');
        $this->dossierEmployeurFieldset->init();

        $this->dossierAutresFiedlset = new DossierAutresFieldset('DossierAutres', ['listChampsAutres' => $statutIntervenant->getChampsAutres()]);
        $this->dossierAutresFiedlset->init();

        $this->setAttribute('id', 'dossier');

        $this->add($this->dossierIdentiteFieldset);
        $this->add($this->dossierAdresseFieldset);
        $this->add($this->dossierContactFiedlset);
        $this->add($this->dossierInseeFiedlset);
        $this->add($this->dossierBancaireFieldset);
        $this->add($this->dossierEmployeurFieldset);
        $this->add($this->dossierAutresFiedlset);

        /**
         * Select pour Statut intervenant customisé
         */
        $statut = new StatutIntervenantSelect('statut', [
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

        /**
         * Csrf
         */
        $this->add(new Csrf('security'));

        /**
         * Submit
         */
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    public function isValid()
    {

        return parent::isValid();
    }



    public function personnaliser(Intervenant $intervenant, $lastHETD = 0)
    {
        $dossier         = $this->getServiceDossier()->getByIntervenant($intervenant);
        $dossierFieldset = $this->get('dossier');
        /* @var $dossierFieldset DossierFieldset */

        if ($lastHETD > 0) {
            /**
             * Si l'intervenant était un vacataire connu l'année précédente, alors
             * la question "Avez-vous exercé une activité..." est retirée puisque la réponse est forcément OUI.
             */
            $dossierFieldset->remove('premierRecrutement');
        }

        /**
         * Pas de sélection de la France par défaut si le numéro INSEE correspond à une naissance hors France.
         */
        if ($dossier->getNumeroInsee() && !$dossier->getNumeroInseeEstProvisoire()) {
            if (substr($dossier->getNumeroInsee(), 5, 2) == '99') {
                $dossierFieldset->get('paysNaissance')->setValue(null);
            }
        }

        return $this;
    }



    /**
     * Redéfinition pour forcer le témoin "premier recrutement" en cas d'absence
     * de l'élément de formulaire.
     */
    /* public function setData($data)
     {
         if (!$this->dossierFieldset->has('premierRecrutement')) {
             $data->dossier['premierRecrutement'] = '0';
         }

         return parent::setData($data);
     }*/


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
    public function setReadOnly(Fieldset $fieldset)
    {
        $elements = $fieldset->getElements();
        /* @var $element Element */

        foreach ($elements as $element) {
            if ($element instanceof Element\Checkbox ||
                $element instanceof Element\Select) {
                $element->setAttribute('disabled', 1);
            } else {
                $element->setAttribute('readonly', 1);
            }
        }

        return $this;
    }



    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;

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
        $spec = [
            'statut' => [
                'required' => false,
            ],
        ];

        return $spec;
    }

}