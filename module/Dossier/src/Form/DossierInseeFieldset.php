<?php

namespace Dossier\Form;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Validator\NumeroINSEEValidator;

/**
 * Description of DossierInseeFieldset
 *
 */
class DossierInseeFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }



    private function addElements()
    {
        $this->add([
                       'name'       => 'numeroInsee',
                       'options'    => [
                           'label'              => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> (clé incluse) <span class="text-danger">*</span>',
                           'use_hidden_element' => false,
                           'checked_value'      => 1,
                           'unchecked_value'    => 0,
                           'label_options'      => [
                               'disable_html_escape' => true,
                           ],
                       ],
                       'attributes' => [
                           'class'     => 'dossierElement',
                           'info_icon' => "Numéro INSEE (sécurité sociale) avec la clé de contrôle",
                           'maxlength' => '15',
                       ],
                       'type'       => 'Text',
                   ]);

        /**
         * Numéro INSEE provisoire
         */
        $this->add([
                       'name'       => 'numeroInseeEstProvisoire',
                       'options'    => [
                           'label'         => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> provisoire ',
                           'label_options' => [
                               'disable_html_escape' => true,
                           ],
                       ],
                       'attributes' => [
                           'class' => 'dossierElement',
                       ],
                       'type'       => 'Checkbox',
                   ]);


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $dossierIdentiteComplementaireFieldset = $this->getOption('dossierIdentiteComplementaireFieldset');
        $dossierIdentiteFieldset               = $this->getOption('dossierIdentiteFieldset');
        $departementDeNaissance                = $dossierIdentiteComplementaireFieldset->get('departementNaissance')->getValue();
        $paysDeNaissance                       = $dossierIdentiteComplementaireFieldset->get('paysNaissance')->getValue();
        $dateDeNaissance                       = $dossierIdentiteFieldset->get('dateNaissance')->getValue();
        $civilite                              = $dossierIdentiteFieldset->get('civilite')->getValue();


        $numeroInseeProvisoire = (bool)$this->get('numeroInseeEstProvisoire')->getValue();

        $spec = [
            'numeroInsee'              => [
                'required'   => false,
                'validators' => [
                    new NumeroINSEEValidator([
                                                 'provisoire'             => $numeroInseeProvisoire,
                                                 'departementDeNaissance' => $departementDeNaissance,
                                                 'paysDeNaissance'        => $paysDeNaissance,
                                                 'dateDeNaissance'        => $dateDeNaissance,
                                                 'civilite'               => $civilite,
                                             ]),
                ],
            ],
            'numeroInseeEstProvisoire' => [
                'required' => false,
            ],
        ];


        return $spec;
    }
}