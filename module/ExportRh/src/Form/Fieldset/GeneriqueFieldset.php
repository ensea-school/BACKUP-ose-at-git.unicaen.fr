<?php

namespace ExportRh\Form\Fieldset;

use Application\Form\AbstractFieldset;

class GeneriqueFieldset extends AbstractFieldset
{

    public function init()
    {

        //Nom usuel
        $this->add([
            'name'       => 'civilite',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Civilité",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Nom usuel
        $this->add([
            'name'       => 'nomUsuel',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Nom usuel",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Prénom
        $this->add([
            'name'       => 'prenom',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Prénom",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Date de naissance
        $this->add([
            'name'       => 'dateNaissance',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Date de naissance",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Nationalité
        $this->add([
            'name'       => 'nationalite',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Nationalité",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Adresse principale
        $this->add([
            'name'       => 'adressePrincipale',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Adresse principale",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Téléphone pro
        $this->add([
            'name'       => 'telPro',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Téléphone pro",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Téléphone perso
        $this->add([
            'name'       => 'telPerso',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Téléphone perso",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Email pro
        $this->add([
            'name'       => 'emailPro',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Email pro",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //Email perso
        $this->add([
            'name'       => 'emailPerso',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Email perso",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //numero INSEE
        $this->add([
            'name'       => 'numeroInsee',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "Numéro INSEE",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //IBAN
        $this->add([
            'name'       => 'iban',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "IBAN",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);

        //BIC
        $this->add([
            'name'       => 'bic',
            'type'       => 'Checkbox',
            'options'    => [
                'label' => "BIC",
            ],
            'attributes' => [
                'value' => 1,
            ],
        ]);


        return $this;
    }


    public function getInputFilterSpecification()
    {

        $spec = [


        ];

        return $spec;
    }
}