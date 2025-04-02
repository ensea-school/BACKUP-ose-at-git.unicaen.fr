<?php

namespace Dossier\Form;

use Application\Constants;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Entity\Db\IntervenantDossier;
use Dossier\Validator\DepartementNaissanceValidator;
use Dossier\Validator\PaysNaissanceValidator;
use Intervenant\Entity\Db\Statut;
use Intervenant\Service\CiviliteServiceAwareTrait;
use Intervenant\Service\SituationMatrimonialeServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Validator\Date as DateValidator;
use Lieu\Service\DepartementServiceAwareTrait;
use Lieu\Service\PaysServiceAwareTrait;

/**
 * Description of DossierIdentiteComplementaireFieldset
 *
 */
class DossierIdentiteComplementaireFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use SituationMatrimonialeServiceAwareTrait;

    private static $franceId;



    public function init()
    {


        /**
         * Pays de naissance
         */
        $this->add([
            'name'       => 'paysNaissance',
            'options'    => [
                'label'         => 'Pays de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->get('paysNaissance')
            ->setValueOptions(['' => '(Sélectionnez un pays)'] + \UnicaenApp\Util::collectionAsOptions($this->getServicePays()->getList()));

        /**
         * Pays nationalité
         */
        $this->add([
            'name'       => 'paysNationalite',
            'options'    => [
                'label'         => 'Pays de Nationalité <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        $this->get('paysNationalite')
            ->setValueOptions(['' => '(Sélectionnez une nationalité)'] + \UnicaenApp\Util::collectionAsOptions($this->getServicePays()->getListValide()));


        /**
         * Département de naissance
         */
        $this->add([
            'name'       => 'departementNaissance',
            'options'    => [
                'label'         => 'Département de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'info_icon'        => "Uniquement si votre pays de naissance est la France.",
                'class'            => 'selectpicker dossierElement',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);


        $this->get('departementNaissance')
            ->setValueOptions(['' => '(Sélectionnez un département)'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceDepartement()->getList()));

        /**
         * Ville de naissance
         */
        $this->add([
            'name'       => 'villeNaissance',
            'options'    => [
                'label'         => 'Ville de naissance <span class="text-danger">*</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],],
            'attributes' => [
                'class' => 'dossierElement',
            ],
            'type'       => 'Text',
        ]);

        /**
         * Situation matrimoniale
         */
        $this->add([
                       'name'       => 'situationMatrimoniale',
                       'options'    => [
                           'label'         => 'Situation matrimoniale',
                           'label_options' => ['disable_html_escape' => true],
                       ],
                       'attributes' => [
                           'class' => 'dossierElement',
                           'id'    => 'situationMatrimoniale',
                       ],
                       'type'       => 'Select',
                   ]);

        $this->get('situationMatrimoniale')
            ->setValueOptions(['' => '- NON RENSEIGNÉ -'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceSituationMatrimoniale()->getList()));

        //Gestion des labels selon les règles du statut intervenant sur les données contact
        $dossierIntervenant       = $this->getOption('dossierIntervenant');
        $statutDossierIntervenant = $dossierIntervenant->getStatut();

        /**
         * @var $statutDossierIntervenant Statut
         * @var $dossierIntervenant       IntervenantDossier
         */

        if ($statutDossierIntervenant->getDossierSituationMatrimoniale()) {
            $this->get('situationMatrimoniale')->setLabel('Situation matrimoniale <span class="text-danger">*</span>');
        }


        $this->add([
                       'name'       => 'dateSituationMatrimoniale',
                       'options'    => [
                           'label'         => 'depuis le',
                           'label_options' => [
                               'disable_html_escape' => true,
                           ],
                       ],
                       'attributes' => [
                           'placeholder' => "jj/mm/aaaa",
                           'class'       => 'dossierElement',
                           'id'          => 'dateSituationMatrimoniale',

                       ],
                       'type'       => 'Date',
                   ]);

        if ($statutDossierIntervenant->getDossierSituationMatrimoniale()) {
            $this->get('dateSituationMatrimoniale')->setLabel('depuis le <span class="text-danger">*</span>');
        }


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $paysNaissanceId = (int)$this->get('paysNaissance')->getValue();
        $dossierIdentiteFieldset               = $this->getOption('dossierIdentiteFieldset');
        $dateDeNaissance                       = $dossierIdentiteFieldset->get('dateNaissance')->getValue();


        return [
            'paysNaissance'        => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new PaysNaissanceValidator(['service' => $this->getServicePays(),
                                                'dateDeNaissance' => $dateDeNaissance]),
                ],
            ],
            'paysNationalite'      => [
                'required'    => false,
                'allow_empty' => true,
            ],
            'departementNaissance' => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new DepartementNaissanceValidator(),
                ],
            ],
            'villeNaissance'       => [
                'required' => false,
            ],
            'situationMatrimoniale'     => [
                'required' => false,
            ],
            'dateSituationMatrimoniale' => [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    new DateValidator(),
                ],
            ],

        ];

    }
}
