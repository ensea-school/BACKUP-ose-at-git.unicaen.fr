<?php

namespace ExportRh\Form;

use Application\Form\AbstractForm;
use ExportRh\Form\Fieldset\GeneriqueFieldset;
use Zend\Form\Fieldset;

class ExportRhForm extends AbstractForm
{

    protected $fieldsetConnecteur = null;



    public function __construct(?Fieldset $fieldsetConnecteur)
    {
        $this->fieldsetConnecteur = $fieldsetConnecteur;
        parent::__construct('ExportRhForm', []);
    }



    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());
        //Partie générique du formulaire
        $generiqueFieldset = new GeneriqueFieldset('generiqueFieldset', []);
        $this->add($generiqueFieldset->init());
        //Partie sépcifique au connecteur SI RH
        $this->add($this->fieldsetConnecteur->init());


        $this->add([
            'name'       => 'submit - button',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn - primary',
            ],
        ]);
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [];
    }
}

