<?php

namespace Mission\Form;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\OffreEmploi;
use OffreFormation\Entity\Db\ElementPedagogique;
use Paiement\Entity\Db\TauxRemu;
use Mission\Entity\Db\TypeMission;


/**
 * Description of OffreEmploiForm
 *
 * @author Antony Le Courtes  <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;

    public function init ()
    {
        $this->spec(OffreEmploi::class, ['intervenant', 'autoValidation', 'validation']);

        $this->spec(['description' => ['type' => 'Textarea']]);

        $this->build();

        $tmDql       = "SELECT tm FROM " . TypeMission::class . " tm WHERE tm.histoDestruction IS NULL AND tm.annee = :annee";
        $tmDqlParams = ['annee' => $this->getServiceContext()->getAnnee()];

        $this->setValueOptions('typeMission', $tmDql, $tmDqlParams);

        $sDql = $this->getServiceStructure()->finderByHistorique();
        $this->getServiceStructure()->finderByEnseignement($sDql);
        if ($this->getServiceContext()->getSelectedIdentityRole()->getStructure()) {
            $this->getServiceStructure()->finderByRole($this->getServiceContext()->getSelectedIdentityRole(), $sDql);
        }
        $structure = $this->getServiceStructure()->getList($sDql);


        $this->setValueOptions('structure', $structure);
        $this->get('structure')->setAttributes(['class' => 'selectpicker', 'data-live-search' => true]);

        $this->setLabels([
            'structure'    => 'Composante proposant l\'offre',
            'typeMission'  => 'Type de mission',
            'dateDebut'    => 'Date de début',
            'dateFin'      => 'Date de fin',
            'dateLimite'   => 'Date limite de candidature',
            'nombreHeures' => 'Nombre d\'heure(s)',
            'nombrePostes' => 'Nombre de poste(s)',
            'description'  => 'Descriptif de l\'offre',
            'titre'        => 'Titre de l\'offre',
        ]);

        $this->spec([
            'dateLimite'   => [
                'input' => [
                    'required' => true,
                ],
            ],
            'dateDebut'    => [
                'input' => [
                    'required' => true,
                ],
            ],
            'dateFin'      => [
                'input' => [
                    'required' => true,
                ],
            ],
            'nombreHeures' => [
                'input' => [
                    'required' => true,
                ],
            ],
            'typeMission'  => [
                'input' => [
                    'required' => true,
                ],
            ],
            'titre'        => [
                'input' => [
                    'required' => true,
                ],
            ],
            'description'  => [
                'input' => [
                    'required' => true,
                ],
            ],


        ]);


        $this->addSubmit();
    }

}