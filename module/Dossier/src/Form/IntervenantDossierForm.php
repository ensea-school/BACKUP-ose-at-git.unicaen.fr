<?php

namespace Dossier\Form;

use Application\Form\AbstractFieldset;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Hydrator\IntervenantDossierHydrator;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Lieu\Form\AdresseFieldset;

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

    protected ?AbstractFieldset $dossierIdentiteFieldset = null;

    protected ?AbstractFieldset $dossierIdentiteComplementaireFieldset = null;

    protected ?AbstractFieldset $dossierAdresseFieldset = null;

    protected ?AbstractFieldset $dossierStatutFieldset = null;

    protected ?AbstractFieldset $dossierContactFieldset = null;

    protected ?AbstractFieldset $dossierInseeFieldset = null;

    protected ?AbstractFieldset $dossierBancaireFieldset = null;

    protected ?AbstractFieldset $dossierEmployeurFieldset = null;

    protected ?AbstractFieldset $dossierAutresFiedlset = null;

    protected Intervenant $intervenant;



    public function initForm()
    {


        $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($this->intervenant);
        $statut             = $this->intervenant->getStatut();
        $intervenant        = $dossierIntervenant->getIntervenant();

        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new IntervenantDossierHydrator();
        $this->setHydrator($hydrator);

        $options = [
            'dossierIntervenant'                    => $dossierIntervenant,
            'dossierIdentiteFieldset'               => &$this->dossierIdentiteFieldset,
            'dossierIdentiteComplementaireFieldset' => &$this->dossierIdentiteComplementaireFieldset,
        ];


        $blocDonneesPersonnelles = [
            'dossierStatut'                 => 1,
            'dossierIdentite'               => 1,
            'dossierIdentiteComplementaire' => $statut->getDossierIdentiteComplementaire(),
            'dossierContact'                => $statut->getDossierContact(),
            'dossierAdresse'                => $statut->getDossierAdresse(),
            'dossierInsee'                  => $statut->getDossierInsee(),
            'dossierBancaire'               => $statut->getDossierBanque(),
            'dossierEmployeur'              => $statut->getDossierEmployeur(),
        ];

        foreach ($blocDonneesPersonnelles as $blocName => $blocStep) {
            if ($blocStep === 1) {
                $propertyName            = $blocName . 'Fieldset';
                $fieldsetConstructorName = '\\Dossier\\Form\\' . ucfirst($blocName) . 'Fieldset';
                $fieldsetName            = ucfirst($blocName);
                if ($blocName == 'dossierStatut') {
                    $this->$propertyName = new $fieldsetConstructorName($fieldsetName, [
                        'intervenant' => $intervenant,
                        'statut'      => $statut,
                    ]);
                } elseif ($blocName == "dossierAdresse") {
                    $fieldsetConstructorName = '\\Lieu\\Form\\AdresseFieldset';
                    $this->$propertyName     = new $fieldsetConstructorName($fieldsetName, $options);

                } else {
                    $this->$propertyName = new $fieldsetConstructorName($fieldsetName, $options);
                }
                $this->$propertyName->init();
                $this->add($this->$propertyName);
            }
        }

        $this->setAttribute('id', 'dossier');


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



    public function setIntervenant(Intervenant $intervenant): self
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
    public function getInputFilterSpecification(): array
    {
        return [];
    }

}