<?php

namespace Dossier\Form;

use Application\Entity\Db\Intervenant;
use Application\Form\AbstractFieldset;
use Application\Form\AbstractForm;
use Application\Form\Adresse\AdresseFieldset;
use Dossier\Hydrator\IntervenantDossierHydrator;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Form\Element\Csrf;

/**
 * Formulaire de modification du dossier d'un intervenant extérieur.
 *
 */
class IntervenantDossierForm extends AbstractForm
{
    use StatutServiceAwareTrait;
    use ContextServiceAwareTrait;
    use DossierServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use DossierServiceAwareTrait;

    protected AbstractFieldset $dossierIdentiteFieldset;

    protected AbstractFieldset $dossierIdentiteComplementaireFieldset;

    protected AbstractFieldset $dossierAdresseFieldset;

    protected AbstractFieldset $dossierStatutFieldset;

    protected AbstractFieldset $dossierContactFiedlset;

    protected AbstractFieldset $dossierInseeFiedlset;

    protected AbstractFieldset $dossierBancaireFieldset;

    protected AbstractFieldset $dossierEmployeurFieldset;

    protected AbstractFieldset $dossierAutresFiedlset;

    protected Intervenant      $intervenant;



    public function initForm ()
    {

        $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($this->intervenant);
        $statut             = $this->intervenant->getStatut();
        $intervenant        = $dossierIntervenant->getIntervenant();

        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new IntervenantDossierHydrator();
        $this->setHydrator($hydrator);


        $this->dossierStatutFieldset = new DossierStatutFieldset('DossierStatut', [
            'statut'      => $statut,
            'intervenant' => $intervenant,
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

        if ($statut->getDossierEmployeurFacultatif()) {
            $this->dossierEmployeurFieldset->get('employeur')->setLabel('Employeurs :');
        }

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

        return $this;
    }



    public function setIntervenant (Intervenant $intervenant): self
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link \Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification (): array
    {
        return [];
    }

}