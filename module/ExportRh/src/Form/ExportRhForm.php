<?php

namespace ExportRh\Form;

use Application\Entity\Db\Intervenant;
use Application\Form\AbstractForm;
use ExportRh\Form\Fieldset\GeneriqueFieldset;
use ExportRh\Hydrator\ExportRhHydrator;
use Laminas\Form\Fieldset;

class ExportRhForm extends AbstractForm
{

    protected $fieldsetConnecteur;

    protected $config;



    public function __construct (?Fieldset $fieldsetConnecteur)
    {
        $this->fieldsetConnecteur = $fieldsetConnecteur;

        parent::__construct('ExportRhForm', []);
    }



    public function init ()
    {
        $this->setAttribute('action', $this->getCurrentUrl());
        //Partie générique du formulaire
        $generiqueFieldset = new GeneriqueFieldset('generiqueFieldset', []);
        $this->add($generiqueFieldset->init());
        //Partie sépcifique au connecteur SI RH
        $this->add($this->fieldsetConnecteur->init());

        $hydrator = new ExportRhHydrator();
        $this->setHydrator($hydrator);


        $this->add([
            'name'       => 'submit - button',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn - primary',
            ],
        ]);
    }



    public function setAffectationDefault (Intervenant $intervenant): self
    {
        //Si l'intervenant n'a pas de structure on ne peut pas lui mettre une affectation par défaut pour sa prise en charge ou renouvellement
        if ($intervenant->getStructure()) {
            $intervenantAffectation = $intervenant->getStructure()->getLibelleCourt();
            //On récupére les libellés de composante dispo dans le form
            $elementAffectation = $this->get('connecteurForm')->get('affectation');
            $listeComposantes   = $this->get('connecteurForm')->get('affectation')->getValueOptions();
            foreach ($listeComposantes as $code => $composante) {
                //On prend que le libelle court ed SIHAM pour le comparer au libelle court de OSE
                $libelleAffectation = explode(' ', $composante, 2);
                $libelleCourt       = $libelleAffectation[1];
                if (strtolower($intervenantAffectation) == strtolower($libelleCourt)) {
                    $elementAffectation->setValue($code);
                }
            }
        }

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification ()
    {
        return [];
    }
}

