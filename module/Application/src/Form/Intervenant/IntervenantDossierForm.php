<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Form\AbstractForm;
use Application\Form\Employeur\EmployeurFieldset;
use Application\Form\Adresse\AdresseFieldset;
use Application\Form\Intervenant\Dossier\DossierAutresFieldset;
use Application\Form\Intervenant\Dossier\DossierBancaireFieldset;
use Application\Form\Intervenant\Dossier\DossierContactFieldset;
use Application\Form\Intervenant\Dossier\DossierIdentiteComplementaireFieldset;
use Application\Form\Intervenant\Dossier\DossierIdentiteFieldset;
use Application\Form\Intervenant\Dossier\DossierInseeFieldset;
use Application\Form\Intervenant\Dossier\DossierStatutFieldset;
use Application\Hydrator\IntervenantDossierHydrator;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Validator\NumeroINSEEValidator;
use Laminas\Form\Element\Csrf;

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

    protected $intervenant;



    public function __construct(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        parent::__construct('IntervenantDossierForm', []);
    }



    public function init()
    {

        $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($this->intervenant);
        $statutIntervenant  = $this->intervenant->getStatut();
        $intervenant        = $dossierIntervenant->getIntervenant();

        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new IntervenantDossierHydrator();
        $this->setHydrator($hydrator);


        $this->dossierStatutFieldset = new DossierStatutFieldset('DossierStatut', [
            'statutIntervenant' => $statutIntervenant,
            'intervenant'       => $intervenant,
        ]);
        $this->dossierStatutFieldset->init();

        $this->dossierIdentiteFieldset = new DossierIdentiteFieldset('DossierIdentite');
        $this->dossierIdentiteFieldset->init();

        $this->dossierIdentiteComplementaireFieldset = new DossierIdentiteComplementaireFieldset('DossierIdentiteComplementaire');
        $this->dossierIdentiteComplementaireFieldset->init();

        $this->dossierAdresseFieldset = new AdresseFieldset('DossierAdresse');
        $this->dossierAdresseFieldset->init();

        $options                      = [
            'dossierIntervenant' => $dossierIntervenant,
        ];
        $this->dossierContactFiedlset = new DossierContactFieldset('DossierContact', $options);
        $this->dossierContactFiedlset->init();

        $options                    = [
            'dossierIdentiteComplementaireFieldset' => $this->dossierIdentiteComplementaireFieldset,
            'dossierIdentiteFieldset'               => $this->dossierIdentiteFieldset,
        ];
        $this->dossierInseeFiedlset = new DossierInseeFieldset('DossierInsee', $options);
        $this->dossierInseeFiedlset->init();

        $this->dossierBancaireFieldset = new DossierBancaireFieldset('DossierBancaire');
        $this->dossierBancaireFieldset->init();

        $this->dossierEmployeurFieldset = new EmployeurFieldset('DossierEmployeur');
        $this->dossierEmployeurFieldset->init();

        $this->dossierAutresFiedlset = new DossierAutresFieldset('DossierAutres', ['listChampsAutres' => $dossierIntervenant->getStatut()->getChampsAutres()]);
        $this->dossierAutresFiedlset->init();


        $this->setAttribute('id', 'dossier');

        $this->add($this->dossierStatutFieldset);
        $this->add($this->dossierIdentiteFieldset);
        $this->add($this->dossierIdentiteComplementaireFieldset);
        $this->add($this->dossierAdresseFieldset);
        $this->add($this->dossierContactFiedlset);
        $this->add($this->dossierInseeFiedlset);
        $this->add($this->dossierBancaireFieldset);
        $this->add($this->dossierEmployeurFieldset);
        $this->add($this->dossierAutresFiedlset);


        /**
         * Csrf
         */
        $this->add(new Csrf('security'));

        /**
         * Submit
         */
        $this->add([
            'name'       => 'submit-button',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    public function isValid(): bool
    {

        return parent::isValid();
    }



    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;

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
        return [];
    }

}